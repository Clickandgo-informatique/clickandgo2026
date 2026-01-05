#!/bin/sh
set -e

echo "⏳ Waiting for Postgres..."
until pg_isready -h postgres -p 5432 -U postgres; do
  echo "Postgres not ready yet..."
  sleep 1
done

echo "🚀 Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --verbose

echo "🌱 Loading fixtures..."
if [ "$APP_ENV" = "dev" ]; then
  php bin/console doctrine:fixtures:load --no-interaction --verbose
fi

echo "✅ Starting PHP-FPM..."
exec php-fpm -F
