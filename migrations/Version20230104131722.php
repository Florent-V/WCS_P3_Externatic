<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104131722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointement DROP FOREIGN KEY FK_BD9991CD6199E97A');
        $this->addSql('DROP INDEX IDX_BD9991CD6199E97A ON appointement');
        $this->addSql('ALTER TABLE appointement CHANGE consultant_id_id consultant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointement ADD CONSTRAINT FK_BD9991CD44F779A2 FOREIGN KEY (consultant_id) REFERENCES externatic_consultant (id)');
        $this->addSql('CREATE INDEX IDX_BD9991CD44F779A2 ON appointement (consultant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointement DROP FOREIGN KEY FK_BD9991CD44F779A2');
        $this->addSql('DROP INDEX IDX_BD9991CD44F779A2 ON appointement');
        $this->addSql('ALTER TABLE appointement CHANGE consultant_id consultant_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appointement ADD CONSTRAINT FK_BD9991CD6199E97A FOREIGN KEY (consultant_id_id) REFERENCES externatic_consultant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BD9991CD6199E97A ON appointement (consultant_id_id)');
    }
}
