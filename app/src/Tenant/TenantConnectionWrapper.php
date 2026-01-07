<?php

namespace App\Tenant;

use Doctrine\DBAL\Connection;

class TenantConnectionWrapper extends Connection
{
    public function __construct(string $placeholderUrl)
    {
        $realConnection = TenantConnectionFactory::createConnection($placeholderUrl);

        parent::__construct(
            $realConnection->getParams(),
            $realConnection->getDriver(),
            $realConnection->getConfiguration(),
            $realConnection->getEventManager()
        );
    }
}
