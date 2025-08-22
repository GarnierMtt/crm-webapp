<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250822121121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE relation_contact_adresse (id SERIAL NOT NULL, contact_id INT DEFAULT NULL, adresse_id INT NOT NULL, role VARCHAR(255) NOT NULL, notes TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C5DE3D8FE7A1254A ON relation_contact_adresse (contact_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C5DE3D8F4DE7DC5C ON relation_contact_adresse (adresse_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_contact_adresse ADD CONSTRAINT FK_C5DE3D8FE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_contact_adresse ADD CONSTRAINT FK_C5DE3D8F4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse ADD societe_id INT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse ADD nom_site VARCHAR(255) NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C35F0816FCF77503 ON adresse (societe_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_contact_adresse DROP CONSTRAINT FK_C5DE3D8FE7A1254A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_contact_adresse DROP CONSTRAINT FK_C5DE3D8F4DE7DC5C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE relation_contact_adresse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse DROP CONSTRAINT FK_C35F0816FCF77503
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C35F0816FCF77503
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse DROP societe_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse DROP nom_site
        SQL);
    }
}
