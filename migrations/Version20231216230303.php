<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231216230303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, content LONGTEXT NOT NULL, flags LONGTEXT NOT NULL, flag1 TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment_post (attachment_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_A2F978B2464E68B (attachment_id), INDEX IDX_A2F978B24B89032C (post_id), PRIMARY KEY(attachment_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attachment_post ADD CONSTRAINT FK_A2F978B2464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attachment_post ADD CONSTRAINT FK_A2F978B24B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment_post DROP FOREIGN KEY FK_A2F978B2464E68B');
        $this->addSql('ALTER TABLE attachment_post DROP FOREIGN KEY FK_A2F978B24B89032C');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE attachment_post');
    }
}
