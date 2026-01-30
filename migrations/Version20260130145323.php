<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260130145323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE communes (id SERIAL NOT NULL, fk_pays_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, code_postal INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C5EE2A556CB7F68 ON communes (fk_pays_id)');
        $this->addSql('CREATE TABLE pays (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE communes ADD CONSTRAINT FK_5C5EE2A556CB7F68 FOREIGN KEY (fk_pays_id) REFERENCES pays (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE communes DROP CONSTRAINT FK_5C5EE2A556CB7F68');
        $this->addSql('DROP TABLE communes');
        $this->addSql('DROP TABLE pays');
    }
}
