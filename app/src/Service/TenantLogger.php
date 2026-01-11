<?php

namespace App\Service;

use App\Entity\Main\TenantActionLog;
use App\Entity\Main\TenantDbConfig;
use Doctrine\ORM\EntityManagerInterface;

class TenantLogger
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Log an action for a tenant
     */
    public function log(
        TenantDbConfig $tenant,
        string $action,
        ?string $details = null,
        ?string $performedBy = null
    ): void {
        $log = new TenantActionLog();
        $log->setTenant($tenant);
        $log->setAction($action);
        $log->setDetails($details);
        $log->setPerformedBy($performedBy ?? 'system');

        $this->em->persist($log);
        $this->em->flush();
    }

    /**
     * Log an action not tied to a specific tenant (optional)
     */
    public function logSystem(?TenantDbConfig $tenant, string $action, ?string $details = null): void
    {
        if ($tenant === null) {
            // Pas de tenant → on ne log rien pour l’instant
            return;
        }

        $this->log($tenant, $action, $details, 'system');
    }
}
