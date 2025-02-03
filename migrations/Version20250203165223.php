<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203165223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE thread ADD create_date DATETIME DEFAULT NULL, ADD update_date DATETIME DEFAULT NULL');
        $this->addSql('UPDATE thread SET create_date = CURRENT_TIMESTAMP, update_date = CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE thread CHANGE create_date create_date DATETIME NOT NULL, CHANGE update_date update_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE thread DROP create_date, DROP update_date');
    }
}
