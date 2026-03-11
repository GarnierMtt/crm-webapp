<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226142527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT fk_7ce748ad84a3df0');
        $this->addSql('DROP INDEX idx_7ce748ad84a3df0');
        $this->addSql('ALTER TABLE reset_password_request RENAME COLUMN fk_utilisateurs_id TO user_id');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request RENAME COLUMN user_id TO fk_utilisateurs_id');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT fk_7ce748ad84a3df0 FOREIGN KEY (fk_utilisateurs_id) REFERENCES utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_7ce748ad84a3df0 ON reset_password_request (fk_utilisateurs_id)');
    }
}
