<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203141702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment ADD content_id INT NOT NULL, ADD name_id INT NOT NULL, DROP name, DROP content, DROP flags, DROP flag1, DROP encode_key');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB84A0A3ED FOREIGN KEY (content_id) REFERENCES encryptable_string (id)');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB71179CD6 FOREIGN KEY (name_id) REFERENCES encryptable_string (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_795FD9BB84A0A3ED ON attachment (content_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_795FD9BB71179CD6 ON attachment (name_id)');
        $this->addSql('ALTER TABLE category ADD name_id INT NOT NULL, DROP name, DROP flags, DROP encode_key');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C171179CD6 FOREIGN KEY (name_id) REFERENCES encryptable_string (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C171179CD6 ON category (name_id)');
        $this->addSql('ALTER TABLE post ADD content_id INT NOT NULL, DROP create_date, DROP content, DROP iv, DROP tag, DROP edit_date');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D84A0A3ED FOREIGN KEY (content_id) REFERENCES encryptable_string (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D84A0A3ED ON post (content_id)');
        $this->addSql('ALTER TABLE thread ADD title_id INT NOT NULL, DROP encode_key, DROP create_date, DROP update_date, DROP title, DROP iv, DROP tag');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83A9F87BD FOREIGN KEY (title_id) REFERENCES encryptable_string (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_31204C83A9F87BD ON thread (title_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BB84A0A3ED');
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BB71179CD6');
        $this->addSql('DROP INDEX UNIQ_795FD9BB84A0A3ED ON attachment');
        $this->addSql('DROP INDEX UNIQ_795FD9BB71179CD6 ON attachment');
        $this->addSql('ALTER TABLE attachment ADD name LONGTEXT NOT NULL, ADD content LONGTEXT NOT NULL, ADD flags LONGTEXT NOT NULL, ADD flag1 TINYINT(1) NOT NULL, ADD encode_key VARCHAR(255) NOT NULL, DROP content_id, DROP name_id');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C171179CD6');
        $this->addSql('DROP INDEX UNIQ_64C19C171179CD6 ON category');
        $this->addSql('ALTER TABLE category ADD name LONGTEXT NOT NULL, ADD flags LONGTEXT NOT NULL, ADD encode_key VARCHAR(255) NOT NULL, DROP name_id');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D84A0A3ED');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D84A0A3ED ON post');
        $this->addSql('ALTER TABLE post ADD create_date DATETIME NOT NULL, ADD content LONGTEXT NOT NULL, ADD iv LONGTEXT NOT NULL, ADD tag LONGTEXT NOT NULL, ADD edit_date DATETIME DEFAULT NULL, DROP content_id');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83A9F87BD');
        $this->addSql('DROP INDEX UNIQ_31204C83A9F87BD ON thread');
        $this->addSql('ALTER TABLE thread ADD encode_key VARCHAR(255) NOT NULL, ADD create_date DATETIME NOT NULL, ADD update_date DATETIME NOT NULL, ADD title LONGTEXT NOT NULL, ADD iv LONGTEXT NOT NULL, ADD tag LONGTEXT NOT NULL, DROP title_id');
    }
}
