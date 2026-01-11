<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tenant_action_log')]
class TenantActionLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TenantDbConfig::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private TenantDbConfig $tenant;

    #[ORM\Column(length: 50)]
    private string $action; // create, migrate, delete, suspend, error, etc.

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $details = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $performedBy = null; // email, username, system, cron, etc.

    #[ORM\Column]
    private \DateTimeImmutable $performedAt;

    public function __construct()
    {
        $this->performedAt = new \DateTimeImmutable();
    }

    // Getters / setters...

    public function getId(): ?int { return $this->id; }

    public function getTenant(): TenantDbConfig { return $this->tenant; }
    public function setTenant(TenantDbConfig $tenant): self { $this->tenant = $tenant; return $this; }

    public function getAction(): string { return $this->action; }
    public function setAction(string $action): self { $this->action = $action; return $this; }

    public function getDetails(): ?string { return $this->details; }
    public function setDetails(?string $details): self { $this->details = $details; return $this; }

    public function getPerformedBy(): ?string { return $this->performedBy; }
    public function setPerformedBy(?string $performedBy): self { $this->performedBy = $performedBy; return $this; }

    public function getPerformedAt(): \DateTimeImmutable { return $this->performedAt; }
}
