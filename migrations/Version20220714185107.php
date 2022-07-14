<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714185107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE timeline (id INT AUTO_INCREMENT NOT NULL, timeline_category_id INT DEFAULT NULL, date DATE DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, INDEX IDX_46FEC6666185C660 (timeline_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timeline_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, date_range VARCHAR(50) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_C7AA1EED2C2AC5D3 (translatable_id), UNIQUE INDEX timeline_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE timeline ADD CONSTRAINT FK_46FEC6666185C660 FOREIGN KEY (timeline_category_id) REFERENCES timeline_category (id)');
        $this->addSql('ALTER TABLE timeline_translation ADD CONSTRAINT FK_C7AA1EED2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES timeline (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timeline_translation DROP FOREIGN KEY FK_C7AA1EED2C2AC5D3');
        $this->addSql('DROP TABLE timeline');
        $this->addSql('DROP TABLE timeline_translation');
    }
}
