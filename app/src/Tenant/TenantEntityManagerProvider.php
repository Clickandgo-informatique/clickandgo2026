<?php

namespace App\Tenant;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class TenantEntityManagerProvider
{
    public function __construct(private TenantContext $context) {}

    public function getEntityManager(): EntityManager
    {
        $tenant = $this->context->getRequiredTenant();

        $connection = DriverManager::getConnection([
            'url' => sprintf(
                'pgsql://%s:%s@%s:%s/%s',
                $tenant->getDbUser(),
                $tenant->getDbPassword(),
                $tenant->getDbHost(),
                $tenant->getDbPort(),
                $tenant->getDbName()
            )
        ]);

        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__.'/../Entity/Tenant'],
            true
        );

        return new EntityManager($connection, $config);
    }
}
