<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221023160915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE design ADD background_image_id INT DEFAULT NULL, DROP background_image');
        $this->addSql('ALTER TABLE design ADD CONSTRAINT FK_CD4F5A30E6DA28AA FOREIGN KEY (background_image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD4F5A30E6DA28AA ON design (background_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE design DROP FOREIGN KEY FK_CD4F5A30E6DA28AA');
        $this->addSql('DROP INDEX UNIQ_CD4F5A30E6DA28AA ON design');
        $this->addSql('ALTER TABLE design ADD background_image VARCHAR(255) NOT NULL, DROP background_image_id');
    }
}
