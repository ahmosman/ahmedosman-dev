<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220718173236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, image_filename VARCHAR(255) DEFAULT NULL, order_value INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(50) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_7CA6B2942C2AC5D3 (translatable_id), UNIQUE INDEX project_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_translation ADD CONSTRAINT FK_7CA6B2942C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_slide ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_slide ADD CONSTRAINT FK_61BAE7FC166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_61BAE7FC166D1F9C ON project_slide (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_slide DROP FOREIGN KEY FK_61BAE7FC166D1F9C');
        $this->addSql('ALTER TABLE project_translation DROP FOREIGN KEY FK_7CA6B2942C2AC5D3');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_translation');
        $this->addSql('DROP INDEX IDX_61BAE7FC166D1F9C ON project_slide');
        $this->addSql('ALTER TABLE project_slide DROP project_id');
    }
}
