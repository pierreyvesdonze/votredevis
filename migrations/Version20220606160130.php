<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220606160130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate ADD user_id INT NOT NULL, ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D2EA4607A76ED395 ON estimate (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate DROP FOREIGN KEY FK_D2EA4607A76ED395');
        $this->addSql('DROP INDEX IDX_D2EA4607A76ED395 ON estimate');
        $this->addSql('ALTER TABLE estimate DROP user_id, DROP title');
    }
}
