<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tenant')]
#[ORM\HasLifecycleCallbacks]
class TenantDbConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private string $slug;

    #[ORM\Column]
    private string $dbName;

    #[ORM\Column]
    private string $dbHost;

    #[ORM\Column]
    private int $dbPort = 5432;

    #[ORM\Column]
    private string $dbUser;

    #[ORM\Column]
    private string $dbPassword;

    // ---------------------------------------------------------
    // STATUS MANAGEMENT
    // ---------------------------------------------------------

    #[ORM\Column(length: 20)]
    private string $status = 'active'; // active, suspended, deleting, deleted, error

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    // ---------------------------------------------------------
    // AUDIT LOGS
    // ---------------------------------------------------------

    #[ORM\Column(nullable: true)]
    private ?string $createdBy = null;

    #[ORM\Column(nullable: true)]
    private ?string $updatedBy = null;

    #[ORM\Column(nullable: true)]
    private ?string $deletedBy = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    // ---------------------------------------------------------
    // LIFECYCLE CALLBACKS
    // ---------------------------------------------------------

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ---------------------------------------------------------
    // GETTERS / SETTERS
    // ---------------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function setDbName(string $dbName): self
    {
        $this->dbName = $dbName;
        return $this;
    }

    public function getDbHost(): string
    {
        return $this->dbHost;
    }

    public function setDbHost(string $dbHost): self
    {
        $this->dbHost = $dbHost;
        return $this;
    }

    public function getDbPort(): int
    {
        return $this->dbPort;
    }

    public function setDbPort(int $dbPort): self
    {
        $this->dbPort = $dbPort;
        return $this;
    }

    public function getDbUser(): string
    {
        return $this->dbUser;
    }

    public function setDbUser(string $dbUser): self
    {
        $this->dbUser = $dbUser;
        return $this;
    }

    public function getDbPassword(): string
    {
        return $this->dbPassword;
    }

    public function setDbPassword(string $dbPassword): self
    {
        $this->dbPassword = $dbPassword;
        return $this;
    }

    // STATUS

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    // AUDIT

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?string $deletedBy): self
    {
        $this->deletedBy = $deletedBy;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
