<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220718135110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_slide (id INT AUTO_INCREMENT NOT NULL, order_value INT DEFAULT NULL, image_filename VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_slide_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_77916C372C2AC5D3 (translatable_id), UNIQUE INDEX project_slide_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_slide_translation ADD CONSTRAINT FK_77916C372C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES project_slide (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_slide_translation DROP FOREIGN KEY FK_77916C372C2AC5D3');
        $this->addSql('DROP TABLE project_slide');
        $this->addSql('DROP TABLE project_slide_translation');
    }
}
