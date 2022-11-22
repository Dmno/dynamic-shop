<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221121092230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE design ADD secondary_page_color VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE design ADD product_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE design ADD title_font_size INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE design DROP secondary_page_color');
        $this->addSql('ALTER TABLE design DROP product_title');
        $this->addSql('ALTER TABLE design DROP title_font_size');
    }
}
