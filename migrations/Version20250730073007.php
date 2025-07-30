<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730073007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe DROP CONSTRAINT FK_3C5DD108C18272
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe ADD CONSTRAINT FK_3C5DD108C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe DROP CONSTRAINT fk_3c5dd108c18272
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe ADD CONSTRAINT fk_3c5dd108c18272 FOREIGN KEY (projet_id) REFERENCES projet (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }
}
