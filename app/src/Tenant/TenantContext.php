<?php

namespace App\Tenant;

use App\Entity\Main\Tenant;

class TenantContext
{
    private ?Tenant $tenant = null;

    public function setTenant(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function getRequiredTenant(): Tenant
    {
        if (!$this->tenant) {
            throw new \RuntimeException("No tenant defined");
        }

        return $this->tenant;
    }
}
