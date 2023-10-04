<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004115924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, company_name VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, postal INT NOT NULL, town VARCHAR(60) NOT NULL, siret VARCHAR(255) NOT NULL, INDEX IDX_81398E09A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estimate (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, customer_id INT NOT NULL, date DATE NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_D2EA4607A76ED395 (user_id), INDEX IDX_D2EA46079395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estimate_line (id INT AUTO_INCREMENT NOT NULL, estimate_id INT NOT NULL, description VARCHAR(255) NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, INDEX IDX_9715EDF785F23082 (estimate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, postal VARCHAR(255) NOT NULL, town VARCHAR(255) NOT NULL, siret VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA46079395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE estimate_line ADD CONSTRAINT FK_9715EDF785F23082 FOREIGN KEY (estimate_id) REFERENCES estimate (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate DROP FOREIGN KEY FK_D2EA46079395C3F3');
        $this->addSql('ALTER TABLE estimate_line DROP FOREIGN KEY FK_9715EDF785F23082');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09A76ED395');
        $this->addSql('ALTER TABLE estimate DROP FOREIGN KEY FK_D2EA4607A76ED395');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE estimate');
        $this->addSql('DROP TABLE estimate_line');
        $this->addSql('DROP TABLE user');
    }
}
