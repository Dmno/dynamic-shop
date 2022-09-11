<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220911135909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create design entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE design (id INT AUTO_INCREMENT NOT NULL, logo VARCHAR(255) DEFAULT NULL, background_image VARCHAR(255) DEFAULT NULL, page_color VARCHAR(20) DEFAULT NULL, text_color VARCHAR(20) DEFAULT NULL, secondary_text_color VARCHAR(20) DEFAULT NULL, phone_number INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE design');
    }
}
