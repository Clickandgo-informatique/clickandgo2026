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

    public function tenantExists(string $tenantKey): bool
    {
        return (bool) $this->emMain
            ->getRepository(TenantDbConfig::class)
            ->findOneBy(['tenantKey' => $tenantKey]);
    }

    // 👉 C’EST ICI que tu mets la méthode
    public function getFallbackTenant(): string
    {
        return 'main'; // ou 'default', 'public', etc.
    }

    public function getTenantConfig(string $tenantKey): ?TenantDbConfig
    {
        return $this->emMain
            ->getRepository(TenantDbConfig::class)
            ->findOneBy(['tenantKey' => $tenantKey]);
    }

    public function getTenantConnectionConfig(mixed $identifier): TenantConnectionConfigDTO
    {
        $config = $this->getTenantConfig($identifier);

        if (!$config) {
            return TenantConnectionConfigDTO::fromArgs(
                identifier: $identifier,
                driver: DriverTypeEnum::POSTGRES,
                dbStatus: DatabaseStatusEnum::DATABASE_NOT_CREATED,
                host: 'postgres',
                port: 5432,
                dbname: $identifier . '_db',
                user: 'postgres',
                password: 'postgres'
            );
        }

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
