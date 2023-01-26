<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124205612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recruitment_process ADD externatic_consultant_id INT NOT NULL');
        $this->addSql('ALTER TABLE recruitment_process ADD CONSTRAINT FK_A28F88B7A43CB887 FOREIGN KEY (externatic_consultant_id) REFERENCES externatic_consultant (id)');
        $this->addSql('CREATE INDEX IDX_A28F88B7A43CB887 ON recruitment_process (externatic_consultant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recruitment_process DROP FOREIGN KEY FK_A28F88B7A43CB887');
        $this->addSql('DROP INDEX IDX_A28F88B7A43CB887 ON recruitment_process');
        $this->addSql('ALTER TABLE recruitment_process DROP externatic_consultant_id');
    }
}
