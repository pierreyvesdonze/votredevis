<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607165458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate_line DROP FOREIGN KEY FK_9715EDF785F23082');
        $this->addSql('DROP INDEX IDX_9715EDF785F23082 ON estimate_line');
        $this->addSql('ALTER TABLE estimate_line DROP estimate_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate_line ADD estimate_id INT NOT NULL');
        $this->addSql('ALTER TABLE estimate_line ADD CONSTRAINT FK_9715EDF785F23082 FOREIGN KEY (estimate_id) REFERENCES estimate (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9715EDF785F23082 ON estimate_line (estimate_id)');
    }
}
