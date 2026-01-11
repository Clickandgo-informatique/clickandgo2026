<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111123017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tenant_action_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tenant_action_log (id INT NOT NULL, tenant_id INT NOT NULL, action VARCHAR(50) NOT NULL, details TEXT DEFAULT NULL, performedBy VARCHAR(100) DEFAULT NULL, performedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4A84A02D9033212A ON tenant_action_log (tenant_id)');
        $this->addSql('COMMENT ON COLUMN tenant_action_log.performedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE tenant_action_log ADD CONSTRAINT FK_4A84A02D9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tenant ADD status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE tenant ADD deletedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE tenant ADD createdBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tenant ADD updatedBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tenant ADD deletedBy VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tenant ADD createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE tenant ADD updatedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN tenant.deletedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tenant.createdAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tenant.updatedAt IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tenant_action_log_id_seq CASCADE');
        $this->addSql('ALTER TABLE tenant_action_log DROP CONSTRAINT FK_4A84A02D9033212A');
        $this->addSql('DROP TABLE tenant_action_log');
        $this->addSql('ALTER TABLE tenant DROP status');
        $this->addSql('ALTER TABLE tenant DROP deletedAt');
        $this->addSql('ALTER TABLE tenant DROP createdBy');
        $this->addSql('ALTER TABLE tenant DROP updatedBy');
        $this->addSql('ALTER TABLE tenant DROP deletedBy');
        $this->addSql('ALTER TABLE tenant DROP createdAt');
        $this->addSql('ALTER TABLE tenant DROP updatedAt');
    }
}
