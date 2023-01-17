<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230110130252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_profile DROP salary_min, DROP work_time, DROP remote, DROP period, DROP company, DROP contract_type, CHANGE search_query search_query LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE techno DROP FOREIGN KEY FK_3987EEDC144C2272');
        $this->addSql('DROP INDEX IDX_3987EEDC144C2272 ON techno');
        $this->addSql('ALTER TABLE techno DROP search_profile_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_profile ADD salary_min VARCHAR(45) DEFAULT NULL, ADD work_time VARCHAR(100) DEFAULT NULL, ADD remote TINYINT(1) DEFAULT NULL, ADD period INT DEFAULT NULL, ADD company VARCHAR(255) DEFAULT NULL, ADD contract_type VARCHAR(255) DEFAULT NULL, CHANGE search_query search_query VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE techno ADD search_profile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE techno ADD CONSTRAINT FK_3987EEDC144C2272 FOREIGN KEY (search_profile_id) REFERENCES search_profile (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3987EEDC144C2272 ON techno (search_profile_id)');
    }
}
