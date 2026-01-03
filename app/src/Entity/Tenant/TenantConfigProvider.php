<?php

namespace App\Tenant;

use App\Entity\Main\TenantDbConfig;
use Doctrine\ORM\EntityManagerInterface;
use Hakam\MultiTenancyBundle\Port\TenantConfigProviderInterface;
use Hakam\MultiTenancyBundle\Config\TenantConnectionConfigDTO;
use Hakam\MultiTenancyBundle\Enum\DriverTypeEnum;
use Hakam\MultiTenancyBundle\Enum\DatabaseStatusEnum;

class TenantConfigProvider implements TenantConfigProviderInterface
{
    public function __construct(private EntityManagerInterface $emMain) {}

    public function getTenantConfig(string $tenantKey)
    {
        return $this->emMain
            ->getRepository(TenantDbConfig::class)
            ->findOneBy(['tenantKey' => $tenantKey]);
    }

    public function getTenantConnectionConfig(mixed $identifier): TenantConnectionConfigDTO
    {
        $config = $this->getTenantConfig($identifier);

        if (!$config) {
            // Le tenant n'existe pas encore → base non créée
            return TenantConnectionConfigDTO::fromArgs(
                identifier: $identifier,
                driver: DriverTypeEnum::POSTGRES,
                dbStatus: DatabaseStatusEnum::DATABASE_NOT_CREATED,
                host: 'postgres',
                port: 5432,
                dbname: '',
                user: 'postgres',
                password: 'postgres'
            );
        }

        // Le tenant existe → base créée (mais pas forcément migrée)
        return TenantConnectionConfigDTO::fromArgs(
            identifier: $identifier,
            driver: DriverTypeEnum::POSTGRES,
            dbStatus: DatabaseStatusEnum::DATABASE_CREATED,
            host: 'postgres',
            port: 5432,
            dbname: $config->getDatabaseName(),
            user: 'postgres',
            password: 'postgres'
        );
    }
}
