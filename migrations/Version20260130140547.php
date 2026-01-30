<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260130140547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lien_fibre DROP CONSTRAINT fk_5d1533bba0eb1bd4');
        $this->addSql('ALTER TABLE lien_fibre DROP CONSTRAINT fk_5d1533bbb25eb43a');
        $this->addSql('DROP SEQUENCE adresse_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relation_projet_societe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relation_contact_adresse_id_seq CASCADE');
        $this->addSql('ALTER TABLE adresse DROP CONSTRAINT fk_c35f0816fcf77503');
        $this->addSql('ALTER TABLE relation_contact_adresse DROP CONSTRAINT fk_c5de3d8f4de7dc5c');
        $this->addSql('ALTER TABLE relation_contact_adresse DROP CONSTRAINT fk_c5de3d8fe7a1254a');
        $this->addSql('ALTER TABLE relation_projet_societe DROP CONSTRAINT fk_3c5dd108c18272');
        $this->addSql('ALTER TABLE relation_projet_societe DROP CONSTRAINT fk_3c5dd108fcf77503');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE relation_contact_adresse');
        $this->addSql('DROP TABLE relation_projet_societe');
        $this->addSql('ALTER TABLE contact DROP post');
        $this->addSql('DROP INDEX idx_5d1533bba0eb1bd4');
        $this->addSql('DROP INDEX idx_5d1533bbb25eb43a');
        $this->addSql('ALTER TABLE lien_fibre DROP point_a_id');
        $this->addSql('ALTER TABLE lien_fibre DROP point_b_id');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN is_verified TO active');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE adresse_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relation_projet_societe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relation_contact_adresse_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE adresse (id SERIAL NOT NULL, societe_id INT NOT NULL, pays VARCHAR(255) NOT NULL, commune VARCHAR(255) NOT NULL, code_postal INT NOT NULL, nom_voie VARCHAR(255) NOT NULL, numero_voie VARCHAR(255) DEFAULT NULL, complement TEXT DEFAULT NULL, nom_site VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c35f0816fcf77503 ON adresse (societe_id)');
        $this->addSql('CREATE TABLE relation_contact_adresse (id SERIAL NOT NULL, contact_id INT DEFAULT NULL, adresse_id INT NOT NULL, role VARCHAR(255) NOT NULL, notes TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c5de3d8f4de7dc5c ON relation_contact_adresse (adresse_id)');
        $this->addSql('CREATE INDEX idx_c5de3d8fe7a1254a ON relation_contact_adresse (contact_id)');
        $this->addSql('CREATE TABLE relation_projet_societe (id SERIAL NOT NULL, projet_id INT NOT NULL, societe_id INT DEFAULT NULL, role VARCHAR(255) NOT NULL, notes TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3c5dd108c18272 ON relation_projet_societe (projet_id)');
        $this->addSql('CREATE INDEX idx_3c5dd108fcf77503 ON relation_projet_societe (societe_id)');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT fk_c35f0816fcf77503 FOREIGN KEY (societe_id) REFERENCES societe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation_contact_adresse ADD CONSTRAINT fk_c5de3d8f4de7dc5c FOREIGN KEY (adresse_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation_contact_adresse ADD CONSTRAINT fk_c5de3d8fe7a1254a FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation_projet_societe ADD CONSTRAINT fk_3c5dd108c18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation_projet_societe ADD CONSTRAINT fk_3c5dd108fcf77503 FOREIGN KEY (societe_id) REFERENCES societe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact ADD post VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lien_fibre ADD point_a_id INT NOT NULL');
        $this->addSql('ALTER TABLE lien_fibre ADD point_b_id INT NOT NULL');
        $this->addSql('ALTER TABLE lien_fibre ADD CONSTRAINT fk_5d1533bba0eb1bd4 FOREIGN KEY (point_b_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lien_fibre ADD CONSTRAINT fk_5d1533bbb25eb43a FOREIGN KEY (point_a_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5d1533bba0eb1bd4 ON lien_fibre (point_b_id)');
        $this->addSql('CREATE INDEX idx_5d1533bbb25eb43a ON lien_fibre (point_a_id)');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN active TO is_verified');
    }
}
