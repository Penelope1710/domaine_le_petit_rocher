<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240119094756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, event_status_id INT NOT NULL, name VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, dead_line DATETIME NOT NULL, organizer_name VARCHAR(150) NOT NULL, event_details VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA712469DE2 (category_id), INDEX IDX_3BAE0AA7ED623E80 (event_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_customer (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, customer_id INT NOT NULL, is_organizer TINYINT(1) NOT NULL, INDEX IDX_C7D567D971F7E88B (event_id), INDEX IDX_C7D567D99395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_status (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7ED623E80 FOREIGN KEY (event_status_id) REFERENCES event_status (id)');
        $this->addSql('ALTER TABLE event_customer ADD CONSTRAINT FK_C7D567D971F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_customer ADD CONSTRAINT FK_C7D567D99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA712469DE2');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7ED623E80');
        $this->addSql('ALTER TABLE event_customer DROP FOREIGN KEY FK_C7D567D971F7E88B');
        $this->addSql('ALTER TABLE event_customer DROP FOREIGN KEY FK_C7D567D99395C3F3');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_customer');
        $this->addSql('DROP TABLE event_status');
    }
}
