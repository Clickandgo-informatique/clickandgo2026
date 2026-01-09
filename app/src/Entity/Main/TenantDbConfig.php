<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tenant')]
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

    // -------------------------
    // Getters / Setters
    // -------------------------

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
}
