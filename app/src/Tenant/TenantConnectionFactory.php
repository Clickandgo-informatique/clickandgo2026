<?php

namespace App\Tenant;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use Doctrine\Common\EventManager;

class TenantConnectionFactory
{
    public function __construct(
        private TenantContext $tenantContext
    ) {}

    public function createConnection(string $placeholderUrl): \Doctrine\DBAL\Connection
    {
        $tenant = $this->tenantContext->getRequiredTenant();

        $realUrl = str_replace('placeholder', $tenant->getDbName(), $placeholderUrl);

        return DriverManager::getConnection(
            ['url' => $realUrl],
            new Configuration(),
            new EventManager()
        );
    }
}
