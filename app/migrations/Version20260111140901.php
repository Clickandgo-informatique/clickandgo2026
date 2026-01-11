<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111140901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE PortalUser_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE PortalUser (id INT NOT NULL, tenant_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC992246E7927C74 ON PortalUser (email)');
        $this->addSql('CREATE INDEX IDX_BC9922469033212A ON PortalUser (tenant_id)');
        $this->addSql('ALTER TABLE PortalUser ADD CONSTRAINT FK_BC9922469033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE PortalUser_id_seq CASCADE');
        $this->addSql('ALTER TABLE PortalUser DROP CONSTRAINT FK_BC9922469033212A');
        $this->addSql('DROP TABLE PortalUser');
    }
}
