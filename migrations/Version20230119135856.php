<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119135856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_profile ADD title VARCHAR(255) DEFAULT NULL, ADD work_time TINYINT(1) DEFAULT NULL, ADD period TINYINT(1) DEFAULT NULL, ADD company_id INT DEFAULT NULL, ADD salary_min INT DEFAULT NULL, ADD contract_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_profile DROP title, DROP work_time, DROP period, DROP company_id, DROP salary_min, DROP contract_type');
    }
}
