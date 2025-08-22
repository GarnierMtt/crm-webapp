<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250822095920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE relation_societe_adresse_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_societe_adresse DROP CONSTRAINT fk_9a37918d4de7dc5c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_societe_adresse DROP CONSTRAINT fk_9a37918dfcf77503
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE relation_societe_adresse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet RENAME COLUMN start_date TO date_deb
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet RENAME COLUMN date_end TO date_fin
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet RENAME COLUMN name TO nom
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE societe RENAME COLUMN name TO nom
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE relation_societe_adresse_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE relation_societe_adresse (id SERIAL NOT NULL, societe_id INT NOT NULL, adresse_id INT NOT NULL, description TEXT DEFAULT NULL, nomsite VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9a37918d4de7dc5c ON relation_societe_adresse (adresse_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9a37918dfcf77503 ON relation_societe_adresse (societe_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_societe_adresse ADD CONSTRAINT fk_9a37918d4de7dc5c FOREIGN KEY (adresse_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_societe_adresse ADD CONSTRAINT fk_9a37918dfcf77503 FOREIGN KEY (societe_id) REFERENCES societe (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet RENAME COLUMN start_date TO date_deb
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet RENAME COLUMN date_end TO date_fin
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet RENAME COLUMN nom TO name
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE societe RENAME COLUMN nom TO name
        SQL);
    }
}
