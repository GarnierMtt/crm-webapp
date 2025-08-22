<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250822124514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse ALTER societe_id SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse ALTER nom_site SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe ALTER societe_id DROP NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse ALTER societe_id DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse ALTER nom_site DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE relation_projet_societe ALTER societe_id SET NOT NULL
        SQL);
    }
}
