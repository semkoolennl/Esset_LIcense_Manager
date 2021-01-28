<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201121185827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD request LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD response LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP name');
        $this->addSql('ALTER TABLE order_line ADD quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD product_code VARCHAR(255) DEFAULT NULL, DROP duration');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP request, DROP response');
        $this->addSql('ALTER TABLE order_line DROP quantity');
        $this->addSql('ALTER TABLE product ADD duration INT NOT NULL, DROP product_code');
    }
}
