<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606071907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ext_log_entries (id SERIAL NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data TEXT DEFAULT NULL, username VARCHAR(191) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX log_date_lookup_idx ON ext_log_entries (logged_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN ext_log_entries.data IS '(DC2Type:array)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ext_log_entries
        SQL);
    }
}
