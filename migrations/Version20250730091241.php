<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730091241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP CONSTRAINT fk_50159ca9727aca70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP CONSTRAINT fk_50159ca9a977936c
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_50159ca9727aca70
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_50159ca9a977936c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP parent_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP tree_root
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP lft
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP rgt
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP level
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD parent_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD tree_root INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD lft INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD rgt INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD level INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD CONSTRAINT fk_50159ca9727aca70 FOREIGN KEY (parent_id) REFERENCES projet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD CONSTRAINT fk_50159ca9a977936c FOREIGN KEY (tree_root) REFERENCES projet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_50159ca9727aca70 ON projet (parent_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_50159ca9a977936c ON projet (tree_root)
        SQL);
    }
}
