<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215112852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E544F779A2');
        $this->addSql('DROP INDEX IDX_F65593E544F779A2 ON annonce');
        $this->addSql('ALTER TABLE annonce CHANGE ending_at ending_at DATE DEFAULT NULL, CHANGE created_at created_at DATE NOT NULL, CHANGE consultant_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5F675F31B FOREIGN KEY (author_id) REFERENCES externatic_consultant (id)');
        $this->addSql('CREATE INDEX IDX_F65593E5F675F31B ON annonce (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5F675F31B');
        $this->addSql('DROP INDEX IDX_F65593E5F675F31B ON annonce');
        $this->addSql('ALTER TABLE annonce CHANGE ending_at ending_at DATETIME DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE author_id consultant_id INT NOT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E544F779A2 FOREIGN KEY (consultant_id) REFERENCES externatic_consultant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F65593E544F779A2 ON annonce (consultant_id)');
    }
}
