<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205234522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE log (id INT NOT NULL, author_id INT DEFAULT NULL, action VARCHAR(255) NOT NULL, ip_address VARCHAR(45) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8F3F68C5F675F31B ON log (author_id)');
        $this->addSql('COMMENT ON COLUMN log.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5F675F31B FOREIGN KEY (author_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE log_id_seq CASCADE');
        $this->addSql('ALTER TABLE log DROP CONSTRAINT FK_8F3F68C5F675F31B');
        $this->addSql('DROP TABLE log');
    }
}
