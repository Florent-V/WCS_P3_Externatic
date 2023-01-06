<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104221639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce CHANGE createdAt created_at DATE NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE createdAt created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE recruitment_process CHANGE createdAt created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE createdAt created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce CHANGE created_at createdAt DATE NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE created_at createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE recruitment_process CHANGE created_at createdAt DATETIME NOT NULL');
    }
}
