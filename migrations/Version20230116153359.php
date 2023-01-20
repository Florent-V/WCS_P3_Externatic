<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116153359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recruitment_process ADD company_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recruitment_process ADD CONSTRAINT FK_A28F88B7979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_A28F88B7979B1AD6 ON recruitment_process (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recruitment_process DROP FOREIGN KEY FK_A28F88B7979B1AD6');
        $this->addSql('DROP INDEX IDX_A28F88B7979B1AD6 ON recruitment_process');
        $this->addSql('ALTER TABLE recruitment_process DROP company_id');
    }
}
