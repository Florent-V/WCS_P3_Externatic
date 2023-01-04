<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230102160854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recruitment_process (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, annonce_id INT NOT NULL, status VARCHAR(45) NOT NULL, information LONGTEXT NOT NULL, rate INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_A28F88B78D0EB82 (candidat_id), INDEX IDX_A28F88B78805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recruitment_process ADD CONSTRAINT FK_A28F88B78D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE recruitment_process ADD CONSTRAINT FK_A28F88B78805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE recrutement_process DROP FOREIGN KEY FK_1A56BBDF8D0EB82');
        $this->addSql('ALTER TABLE recrutement_process DROP FOREIGN KEY FK_1A56BBDF8805AB2F');
        $this->addSql('ALTER TABLE appointement DROP FOREIGN KEY FK_BD9991CD280DA1F5');
        $this->addSql('DROP TABLE recrutement_process');
        $this->addSql('ALTER TABLE appointement ADD CONSTRAINT FK_BD9991CD280DA1F5 FOREIGN KEY (recruitment_process_id) REFERENCES recruitment_process (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recrutement_process (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, annonce_id INT NOT NULL, status VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, information LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rate INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_1A56BBDF8D0EB82 (candidat_id), INDEX IDX_1A56BBDF8805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recrutement_process ADD CONSTRAINT FK_1A56BBDF8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE recrutement_process ADD CONSTRAINT FK_1A56BBDF8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE recruitment_process DROP FOREIGN KEY FK_A28F88B78D0EB82');
        $this->addSql('ALTER TABLE recruitment_process DROP FOREIGN KEY FK_A28F88B78805AB2F');
        $this->addSql('DROP TABLE recruitment_process');
        $this->addSql('ALTER TABLE appointement ADD CONSTRAINT FK_BD9991CD280DA1F5 FOREIGN KEY (recruitment_process_id) REFERENCES recrutement_process (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
