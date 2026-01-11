#!/bin/sh
set -e

cd /var/www/html

echo "------------------------------------------------------------"
echo "🧹 Resetting Symfony cache"
echo "------------------------------------------------------------"

rm -rf var/cache/* || true
rm -rf var/log/* || true

php bin/console cache:clear --no-warmup || true
php bin/console cache:warmup || true


echo "------------------------------------------------------------"
echo "⏳ Waiting for PostgreSQL to be ready..."
echo "------------------------------------------------------------"

until pg_isready -h postgres -p 5432 -U postgres > /dev/null 2>&1; do
  sleep 1
done

echo "✅ PostgreSQL is ready!"


echo "------------------------------------------------------------"
echo "🔍 Checking if main database is empty..."
echo "------------------------------------------------------------"

TABLE_COUNT=$(psql "postgresql://postgres:postgres@postgres:5432/main_db" -tAc \
  "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='public';")

echo "📊 Tables found: $TABLE_COUNT"


if [ "$TABLE_COUNT" -eq 0 ]; then
    echo "------------------------------------------------------------"
    echo "🆕 Main DB empty — running migrations + fixtures"
    echo "------------------------------------------------------------"

    php bin/console doctrine:migrations:migrate --no-interaction || true
    php bin/console doctrine:fixtures:load --no-interaction || true
else
    echo "------------------------------------------------------------"
    echo "🔄 Main DB not empty — running migrations only"
    echo "------------------------------------------------------------"

    php bin/console doctrine:migrations:migrate --no-interaction || true
fi


echo "------------------------------------------------------------"
echo "🏢 Managing tenants"
echo "------------------------------------------------------------"

if php bin/console list | grep -q "app:tenant:list"; then

    TENANTS=$(php bin/console app:tenant:list --no-interaction | awk '{print $1}')

    if [ -z "$TENANTS" ]; then
        echo "⚠️  No tenants found. Skipping tenant setup."
    else
        for TENANT in $TENANTS; do
            echo "------------------------------------------------------------"
            echo "🏗️  Setting up tenant: $TENANT"
            echo "------------------------------------------------------------"

            echo "➤ Creating tenant database (if not exists)"
            php bin/console app:tenant:database:create "$TENANT" --no-interaction || true

            echo "➤ Running tenant migrations"
            php bin/console app:tenant:migrate "$TENANT" --no-interaction || true

            if php bin/console list | grep -q "app:tenant:fixtures"; then
                echo "➤ Loading tenant fixtures"
                php bin/console app:tenant:fixtures "$TENANT" --no-interaction || true
            else
                echo "⚠️  No tenant fixtures command found"
            fi
        done
    fi

else
    echo "⚠️  Tenant commands not found. Skipping tenant setup."
fi


echo "------------------------------------------------------------"
echo "✅ Entrypoint completed. Starting PHP-FPM..."
echo "------------------------------------------------------------"

exec php-fpm