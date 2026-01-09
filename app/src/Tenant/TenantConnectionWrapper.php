<?php

namespace App\Tenant;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Configuration;
use Doctrine\Common\EventManager;

class TenantConnectionWrapper extends Connection
{
    public function __construct(
        array $params,
        Configuration $config,
        EventManager $eventManager,
        TenantConnectionFactory $factory
    ) {
        // On récupère l’URL placeholder depuis $params
        $placeholderUrl = $params['url'] ?? null;

        if (!$placeholderUrl) {
            throw new \RuntimeException("Missing 'url' in tenant connection params");
        }

        // On crée la vraie connexion tenant
        $realConnection = $factory->createConnection($placeholderUrl);

        // On remplace les paramètres par ceux de la vraie connexion
        parent::__construct(
            $realConnection->getParams(),
            $realConnection->getDriver(),
            $realConnection->getConfiguration(),
            $realConnection->getEventManager()
        );
    }
}
