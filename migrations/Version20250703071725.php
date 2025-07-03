<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250703071725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE adresse (id SERIAL NOT NULL, pays VARCHAR(255) NOT NULL, commune VARCHAR(255) NOT NULL, code_postal INT NOT NULL, nom_voie VARCHAR(255) NOT NULL, numero_voie VARCHAR(255) DEFAULT NULL, complement TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE contact (id SERIAL NOT NULL, societe_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) DEFAULT NULL, mel VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, post VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4C62E638FCF77503 ON contact (societe_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lien_fibre (id SERIAL NOT NULL, point_a_id INT NOT NULL, point_b_id INT NOT NULL, projet_id INT DEFAULT NULL, nombre_fibres SMALLINT NOT NULL, distance INT NOT NULL, attenuation DOUBLE PRECISION DEFAULT NULL, reference_fibre VARCHAR(255) DEFAULT NULL, reference_operateur VARCHAR(255) DEFAULT NULL, reference_liaison VARCHAR(255) DEFAULT NULL, date_livraison DATE DEFAULT NULL, date_activation DATE DEFAULT NULL, lien_active BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5D1533BBB25EB43A ON lien_fibre (point_a_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5D1533BBA0EB1BD4 ON lien_fibre (point_b_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5D1533BBC18272 ON lien_fibre (projet_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE relation_projet_societe (id SERIAL NOT NULL, projet_id INT NOT NULL, societe_id INT DEFAULT NULL, role TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C5DD108C18272 ON relation_projet_societe (projet_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C5DD108FCF77503 ON relation_projet_societe (societe_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE relation_societe_adresse (id SERIAL NOT NULL, societe_id INT NOT NULL, adresse_id INT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9A37918DFCF77503 ON relation_societe_adresse (societe_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9A37918D4DE7DC5C ON relation_societe_adresse (adresse_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE societe (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, telephone_standard VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact ADD CONSTRAINT FK_4C62E638FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lien_fibre ADD CONSTRAINT FK_5D1533BBB25EB43A FOREIGN KEY (point_a_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lien_fibre ADD CONSTRAINT FK_5D1533BBA0EB1BD4 FOREIGN KEY (point_b_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lien_fibre ADD CONSTRAINT FK_5D1533BBC18272 FOREIGN KEY (projet_id) REFERENCES projet (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe ADD CONSTRAINT FK_3C5DD108C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe ADD CONSTRAINT FK_3C5DD108FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_societe_adresse ADD CONSTRAINT FK_9A37918DFCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_societe_adresse ADD CONSTRAINT FK_9A37918D4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact DROP CONSTRAINT FK_4C62E638FCF77503
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lien_fibre DROP CONSTRAINT FK_5D1533BBB25EB43A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lien_fibre DROP CONSTRAINT FK_5D1533BBA0EB1BD4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lien_fibre DROP CONSTRAINT FK_5D1533BBC18272
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe DROP CONSTRAINT FK_3C5DD108C18272
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe DROP CONSTRAINT FK_3C5DD108FCF77503
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_societe_adresse DROP CONSTRAINT FK_9A37918DFCF77503
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_societe_adresse DROP CONSTRAINT FK_9A37918D4DE7DC5C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE adresse
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contact
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lien_fibre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE relation_projet_societe
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE relation_societe_adresse
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE societe
        SQL);
    }
}
