<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260204154152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE projet_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE lien_fibre_id_seq CASCADE');
        $this->addSql('CREATE TABLE liens_fibre (id SERIAL NOT NULL, fk_projets_id INT DEFAULT NULL, nombre_fibres SMALLINT NOT NULL, distance INT NOT NULL, attenuation DOUBLE PRECISION DEFAULT NULL, reference_fibre VARCHAR(255) DEFAULT NULL, reference_operateur VARCHAR(255) DEFAULT NULL, reference_liaison VARCHAR(255) DEFAULT NULL, date_livraison DATE DEFAULT NULL, date_activation DATE DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4A837D6A914A624 ON liens_fibre (fk_projets_id)');
        $this->addSql('CREATE TABLE projets (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE liens_fibre ADD CONSTRAINT FK_D4A837D6A914A624 FOREIGN KEY (fk_projets_id) REFERENCES projets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lien_fibre DROP CONSTRAINT fk_5d1533bbc18272');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE lien_fibre');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE projet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE lien_fibre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE projet (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, date_deb DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lien_fibre (id SERIAL NOT NULL, projet_id INT DEFAULT NULL, nombre_fibres SMALLINT NOT NULL, distance INT NOT NULL, attenuation DOUBLE PRECISION DEFAULT NULL, reference_fibre VARCHAR(255) DEFAULT NULL, reference_operateur VARCHAR(255) DEFAULT NULL, reference_liaison VARCHAR(255) DEFAULT NULL, date_livraison DATE DEFAULT NULL, date_activation DATE DEFAULT NULL, lien_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_5d1533bbc18272 ON lien_fibre (projet_id)');
        $this->addSql('ALTER TABLE lien_fibre ADD CONSTRAINT fk_5d1533bbc18272 FOREIGN KEY (projet_id) REFERENCES projet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE liens_fibre DROP CONSTRAINT FK_D4A837D6A914A624');
        $this->addSql('DROP TABLE liens_fibre');
        $this->addSql('DROP TABLE projets');
    }
}
