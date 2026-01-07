<?php

namespace App\Tenant;

use App\Entity\Main\Tenant;
use Doctrine\ORM\EntityManagerInterface;

class TenantProvider
{
    public function __construct(private EntityManagerInterface $mainEm)
    {
    }

    public function getTenantBySlug(string $slug): ?Tenant
    {
        return $this->mainEm
            ->getRepository(Tenant::class)
            ->findOneBy(['slug' => $slug]);
    }
}
