<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230110090344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_profile ADD search_query VARCHAR(255) NOT NULL, ADD period INT DEFAULT NULL, ADD company VARCHAR(255) DEFAULT NULL, ADD contract_type VARCHAR(255) DEFAULT NULL, DROP salary_max, DROP title, CHANGE is_remote remote TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_profile ADD salary_max VARCHAR(45) DEFAULT NULL, ADD title VARCHAR(100) DEFAULT NULL, DROP search_query, DROP period, DROP company, DROP contract_type, CHANGE remote is_remote TINYINT(1) DEFAULT NULL');
    }
}
