<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714140817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE timeline_category_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_335E3A242C2AC5D3 (translatable_id), UNIQUE INDEX timeline_category_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE timeline_category_translation ADD CONSTRAINT FK_335E3A242C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES timeline_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE timeline_category DROP name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE timeline_category_translation');
        $this->addSql('ALTER TABLE timeline_category ADD name VARCHAR(50) NOT NULL');
    }
}
