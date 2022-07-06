<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220706205346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, text_id VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_64C19C1698D3548 (text_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heading (id INT AUTO_INCREMENT NOT NULL, text_id VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heading_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_33ACDFF12C2AC5D3 (translatable_id), UNIQUE INDEX heading_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph (id INT AUTO_INCREMENT NOT NULL, text_id VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_7DD39862698D3548 (text_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paragraph_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(50) DEFAULT NULL, description LONGTEXT DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_F8A305DF2C2AC5D3 (translatable_id), UNIQUE INDEX paragraph_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE heading_translation ADD CONSTRAINT FK_33ACDFF12C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES heading (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paragraph_translation ADD CONSTRAINT FK_F8A305DF2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES paragraph (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE heading_translation DROP FOREIGN KEY FK_33ACDFF12C2AC5D3');
        $this->addSql('ALTER TABLE paragraph_translation DROP FOREIGN KEY FK_F8A305DF2C2AC5D3');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE heading');
        $this->addSql('DROP TABLE heading_translation');
        $this->addSql('DROP TABLE paragraph');
        $this->addSql('DROP TABLE paragraph_translation');
    }
}
