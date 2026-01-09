<?php

namespace App\Tenant;

use App\Entity\Main\TenantDbConfig;
use Doctrine\ORM\EntityManagerInterface;

class TenantProvider
{
    public function __construct(private EntityManagerInterface $emMain) {}

    public function getTenantBySlug(string $slug): ?TenantDbConfig
    {
        return $this->emMain
            ->getRepository(TenantDbConfig::class)
            ->findOneBy(['slug' => $slug]);
    }
}
