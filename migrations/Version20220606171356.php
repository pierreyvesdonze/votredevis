<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220606171356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD company_name VARCHAR(255) NOT NULL, ADD street VARCHAR(255) NOT NULL, ADD postal VARCHAR(255) NOT NULL, ADD town VARCHAR(255) NOT NULL, ADD siret VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP company_name, DROP street, DROP postal, DROP town, DROP siret');
    }
}
