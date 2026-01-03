<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tenant_db_config')]
class TenantDbConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Identifiant unique du tenant (ex: "tenant1")
    #[ORM\Column(length: 255, unique: true)]
    private string $tenantKey;

    // Nom de la base PostgreSQL du tenant (ex: "tenant1_db")
    #[ORM\Column(length: 255)]
    private string $databaseName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenantKey(): string
    {
        return $this->tenantKey;
    }

    public function setTenantKey(string $tenantKey): self
    {
        $this->tenantKey = $tenantKey;
        return $this;
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function setDatabaseName(string $databaseName): self
    {
        $this->databaseName = $databaseName;
        return $this;
    }
}
