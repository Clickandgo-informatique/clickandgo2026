<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112123049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE PortalUser_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tenant_action_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tenant_db_config_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE PortalUser (id INT NOT NULL, tenant_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC992246E7927C74 ON PortalUser (email)');
        $this->addSql('CREATE INDEX IDX_BC9922469033212A ON PortalUser (tenant_id)');
        $this->addSql('CREATE TABLE tenant_action_log (id INT NOT NULL, tenant_id INT NOT NULL, action VARCHAR(50) NOT NULL, details TEXT DEFAULT NULL, performedBy VARCHAR(100) DEFAULT NULL, performedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4A84A02D9033212A ON tenant_action_log (tenant_id)');
        $this->addSql('COMMENT ON COLUMN tenant_action_log.performedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tenant_db_config (id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, dbName VARCHAR(255) NOT NULL, dbHost VARCHAR(255) NOT NULL, dbPort INT NOT NULL, dbUser VARCHAR(255) NOT NULL, dbPassword VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL, deletedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, createdBy VARCHAR(255) DEFAULT NULL, updatedBy VARCHAR(255) DEFAULT NULL, deletedBy VARCHAR(255) DEFAULT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updatedAt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E665B59C989D9B62 ON tenant_db_config (slug)');
        $this->addSql('COMMENT ON COLUMN tenant_db_config.deletedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tenant_db_config.createdAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tenant_db_config.updatedAt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE PortalUser ADD CONSTRAINT FK_BC9922469033212A FOREIGN KEY (tenant_id) REFERENCES tenant_db_config (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tenant_action_log ADD CONSTRAINT FK_4A84A02D9033212A FOREIGN KEY (tenant_id) REFERENCES tenant_db_config (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE PortalUser_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tenant_action_log_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tenant_db_config_id_seq CASCADE');
        $this->addSql('ALTER TABLE PortalUser DROP CONSTRAINT FK_BC9922469033212A');
        $this->addSql('ALTER TABLE tenant_action_log DROP CONSTRAINT FK_4A84A02D9033212A');
        $this->addSql('DROP TABLE PortalUser');
        $this->addSql('DROP TABLE tenant_action_log');
        $this->addSql('DROP TABLE tenant_db_config');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
