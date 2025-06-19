<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618150455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD parent_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD start_date DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD date_end DATE DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD lft INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD rgt INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD root INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD level INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD CONSTRAINT FK_50159CA9727ACA70 FOREIGN KEY (parent_id) REFERENCES projet (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_50159CA9727ACA70 ON projet (parent_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP CONSTRAINT FK_50159CA9727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_50159CA9727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP parent_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP start_date
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP date_end
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP lft
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP rgt
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP root
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP level
        SQL);
    }
}
