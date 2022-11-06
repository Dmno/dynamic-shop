<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106135011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE design ADD logo_image_id INT DEFAULT NULL, DROP logo');
        $this->addSql('ALTER TABLE design ADD CONSTRAINT FK_CD4F5A306D947EBB FOREIGN KEY (logo_image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD4F5A306D947EBB ON design (logo_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE design DROP FOREIGN KEY FK_CD4F5A306D947EBB');
        $this->addSql('DROP INDEX UNIQ_CD4F5A306D947EBB ON design');
        $this->addSql('ALTER TABLE design ADD logo VARCHAR(255) NOT NULL, DROP logo_image_id');
    }
}
