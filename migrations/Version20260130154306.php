<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260130154306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE societe_id_seq CASCADE');
        $this->addSql('CREATE TABLE contacts (id SERIAL NOT NULL, fk_societes_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_334015731772B7DE ON contacts (fk_societes_id)');
        $this->addSql('CREATE TABLE societes (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, telephone_standard VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_334015731772B7DE FOREIGN KEY (fk_societes_id) REFERENCES societes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact DROP CONSTRAINT fk_4c62e638fcf77503');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE societe');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE societe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contact (id SERIAL NOT NULL, societe_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_4c62e638fcf77503 ON contact (societe_id)');
        $this->addSql('CREATE TABLE societe (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, telephone_standard VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT fk_4c62e638fcf77503 FOREIGN KEY (societe_id) REFERENCES societe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contacts DROP CONSTRAINT FK_334015731772B7DE');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE societes');
    }
}
