<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213105637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marques (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE materiels (id SERIAL NOT NULL, fk_modeles_id INT NOT NULL, fk_projets_id INT DEFAULT NULL, fk_sites_id INT NOT NULL, fk_liens_fibre_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9C1EBE69F7668A1F ON materiels (fk_modeles_id)');
        $this->addSql('CREATE INDEX IDX_9C1EBE69A914A624 ON materiels (fk_projets_id)');
        $this->addSql('CREATE INDEX IDX_9C1EBE694A10A748 ON materiels (fk_sites_id)');
        $this->addSql('CREATE INDEX IDX_9C1EBE69D241EE61 ON materiels (fk_liens_fibre_id)');
        $this->addSql('CREATE TABLE modeles (id SERIAL NOT NULL, fk_marques_id INT NOT NULL, fk_types_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, numero_serie VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7EAE1448323882AF ON modeles (fk_marques_id)');
        $this->addSql('CREATE INDEX IDX_7EAE1448BC9A7089 ON modeles (fk_types_id)');
        $this->addSql('CREATE TABLE sites (id SERIAL NOT NULL, fk_communes_id INT DEFAULT NULL, fk_societes_id INT NOT NULL, nom VARCHAR(255) NOT NULL, numero_voie VARCHAR(255) NOT NULL, nom_voie VARCHAR(255) NOT NULL, complement VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC00AA63309F1B4E ON sites (fk_communes_id)');
        $this->addSql('CREATE INDEX IDX_BC00AA631772B7DE ON sites (fk_societes_id)');
        $this->addSql('CREATE TABLE sites_contacts (id SERIAL NOT NULL, fk_sites_id INT NOT NULL, fk_contacts_id INT NOT NULL, note TEXT DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2EEBC5384A10A748 ON sites_contacts (fk_sites_id)');
        $this->addSql('CREATE INDEX IDX_2EEBC538186918BA ON sites_contacts (fk_contacts_id)');
        $this->addSql('CREATE TABLE taches (id SERIAL NOT NULL, fk_projets_id INT NOT NULL, fk_societes_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BF2CD98A914A624 ON taches (fk_projets_id)');
        $this->addSql('CREATE INDEX IDX_3BF2CD981772B7DE ON taches (fk_societes_id)');
        $this->addSql('CREATE TABLE taches_utilisateurs (taches_id INT NOT NULL, utilisateurs_id INT NOT NULL, PRIMARY KEY(taches_id, utilisateurs_id))');
        $this->addSql('CREATE INDEX IDX_D2F97DCBB8A61670 ON taches_utilisateurs (taches_id)');
        $this->addSql('CREATE INDEX IDX_D2F97DCB1E969C5 ON taches_utilisateurs (utilisateurs_id)');
        $this->addSql('CREATE TABLE types (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE materiels ADD CONSTRAINT FK_9C1EBE69F7668A1F FOREIGN KEY (fk_modeles_id) REFERENCES modeles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE materiels ADD CONSTRAINT FK_9C1EBE69A914A624 FOREIGN KEY (fk_projets_id) REFERENCES projets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE materiels ADD CONSTRAINT FK_9C1EBE694A10A748 FOREIGN KEY (fk_sites_id) REFERENCES sites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE materiels ADD CONSTRAINT FK_9C1EBE69D241EE61 FOREIGN KEY (fk_liens_fibre_id) REFERENCES liens_fibre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE modeles ADD CONSTRAINT FK_7EAE1448323882AF FOREIGN KEY (fk_marques_id) REFERENCES marques (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE modeles ADD CONSTRAINT FK_7EAE1448BC9A7089 FOREIGN KEY (fk_types_id) REFERENCES types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sites ADD CONSTRAINT FK_BC00AA63309F1B4E FOREIGN KEY (fk_communes_id) REFERENCES communes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sites ADD CONSTRAINT FK_BC00AA631772B7DE FOREIGN KEY (fk_societes_id) REFERENCES societes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sites_contacts ADD CONSTRAINT FK_2EEBC5384A10A748 FOREIGN KEY (fk_sites_id) REFERENCES sites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sites_contacts ADD CONSTRAINT FK_2EEBC538186918BA FOREIGN KEY (fk_contacts_id) REFERENCES contacts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD98A914A624 FOREIGN KEY (fk_projets_id) REFERENCES projets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD981772B7DE FOREIGN KEY (fk_societes_id) REFERENCES societes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taches_utilisateurs ADD CONSTRAINT FK_D2F97DCBB8A61670 FOREIGN KEY (taches_id) REFERENCES taches (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE taches_utilisateurs ADD CONSTRAINT FK_D2F97DCB1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE liens_fibre ADD point_a_id INT NOT NULL');
        $this->addSql('ALTER TABLE liens_fibre ADD point_b_id INT NOT NULL');
        $this->addSql('ALTER TABLE liens_fibre ADD CONSTRAINT FK_D4A837D6B25EB43A FOREIGN KEY (point_a_id) REFERENCES sites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE liens_fibre ADD CONSTRAINT FK_D4A837D6A0EB1BD4 FOREIGN KEY (point_b_id) REFERENCES sites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D4A837D6B25EB43A ON liens_fibre (point_a_id)');
        $this->addSql('CREATE INDEX IDX_D4A837D6A0EB1BD4 ON liens_fibre (point_b_id)');
        $this->addSql('ALTER TABLE projets ADD societe_client_id INT NOT NULL');
        $this->addSql('ALTER TABLE projets ADD CONSTRAINT FK_B454C1DB9A4C98F2 FOREIGN KEY (societe_client_id) REFERENCES societes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B454C1DB9A4C98F2 ON projets (societe_client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE liens_fibre DROP CONSTRAINT FK_D4A837D6B25EB43A');
        $this->addSql('ALTER TABLE liens_fibre DROP CONSTRAINT FK_D4A837D6A0EB1BD4');
        $this->addSql('ALTER TABLE materiels DROP CONSTRAINT FK_9C1EBE69F7668A1F');
        $this->addSql('ALTER TABLE materiels DROP CONSTRAINT FK_9C1EBE69A914A624');
        $this->addSql('ALTER TABLE materiels DROP CONSTRAINT FK_9C1EBE694A10A748');
        $this->addSql('ALTER TABLE materiels DROP CONSTRAINT FK_9C1EBE69D241EE61');
        $this->addSql('ALTER TABLE modeles DROP CONSTRAINT FK_7EAE1448323882AF');
        $this->addSql('ALTER TABLE modeles DROP CONSTRAINT FK_7EAE1448BC9A7089');
        $this->addSql('ALTER TABLE sites DROP CONSTRAINT FK_BC00AA63309F1B4E');
        $this->addSql('ALTER TABLE sites DROP CONSTRAINT FK_BC00AA631772B7DE');
        $this->addSql('ALTER TABLE sites_contacts DROP CONSTRAINT FK_2EEBC5384A10A748');
        $this->addSql('ALTER TABLE sites_contacts DROP CONSTRAINT FK_2EEBC538186918BA');
        $this->addSql('ALTER TABLE taches DROP CONSTRAINT FK_3BF2CD98A914A624');
        $this->addSql('ALTER TABLE taches DROP CONSTRAINT FK_3BF2CD981772B7DE');
        $this->addSql('ALTER TABLE taches_utilisateurs DROP CONSTRAINT FK_D2F97DCBB8A61670');
        $this->addSql('ALTER TABLE taches_utilisateurs DROP CONSTRAINT FK_D2F97DCB1E969C5');
        $this->addSql('DROP TABLE marques');
        $this->addSql('DROP TABLE materiels');
        $this->addSql('DROP TABLE modeles');
        $this->addSql('DROP TABLE sites');
        $this->addSql('DROP TABLE sites_contacts');
        $this->addSql('DROP TABLE taches');
        $this->addSql('DROP TABLE taches_utilisateurs');
        $this->addSql('DROP TABLE types');
        $this->addSql('DROP INDEX IDX_D4A837D6B25EB43A');
        $this->addSql('DROP INDEX IDX_D4A837D6A0EB1BD4');
        $this->addSql('ALTER TABLE liens_fibre DROP point_a_id');
        $this->addSql('ALTER TABLE liens_fibre DROP point_b_id');
        $this->addSql('ALTER TABLE projets DROP CONSTRAINT FK_B454C1DB9A4C98F2');
        $this->addSql('DROP INDEX IDX_B454C1DB9A4C98F2');
        $this->addSql('ALTER TABLE projets DROP societe_client_id');
    }
}
