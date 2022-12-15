<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215105013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75CFE419E2');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103CFE419E2');
        $this->addSql('ALTER TABLE hobbie DROP FOREIGN KEY FK_1D9CA9F7CFE419E2');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B5CFE419E2');
        $this->addSql('CREATE TABLE curriculum (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, skills_id INT NOT NULL, UNIQUE INDEX UNIQ_7BE2A7C38D0EB82 (candidat_id), UNIQUE INDEX UNIQ_7BE2A7C37FF61858 (skills_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curriculum_has_techno (id INT AUTO_INCREMENT NOT NULL, curriculum_id INT DEFAULT NULL, techno_id INT DEFAULT NULL, level VARCHAR(100) NOT NULL, INDEX IDX_E0DB764D5AEA4428 (curriculum_id), INDEX IDX_E0DB764D51F3C1BC (techno_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT FK_7BE2A7C38D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT FK_7BE2A7C37FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id)');
        $this->addSql('ALTER TABLE curriculum_has_techno ADD CONSTRAINT FK_E0DB764D5AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('ALTER TABLE curriculum_has_techno ADD CONSTRAINT FK_E0DB764D51F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id)');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE927FF61858');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE928D0EB82');
        $this->addSql('ALTER TABLE cv_has_techno DROP FOREIGN KEY FK_6A536CD551F3C1BC');
        $this->addSql('ALTER TABLE cv_has_techno DROP FOREIGN KEY FK_6A536CD5CFE419E2');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE cv_has_techno');
        $this->addSql('DROP INDEX IDX_6C3C6D75CFE419E2 ON certification');
        $this->addSql('ALTER TABLE certification CHANGE cv_id curriculum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D755AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('CREATE INDEX IDX_6C3C6D755AEA4428 ON certification (curriculum_id)');
        $this->addSql('DROP INDEX IDX_590C103CFE419E2 ON experience');
        $this->addSql('ALTER TABLE experience CHANGE cv_id curriculum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1035AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('CREATE INDEX IDX_590C1035AEA4428 ON experience (curriculum_id)');
        $this->addSql('DROP INDEX IDX_1D9CA9F7CFE419E2 ON hobbie');
        $this->addSql('ALTER TABLE hobbie CHANGE cv_id curriculum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hobbie ADD CONSTRAINT FK_1D9CA9F75AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('CREATE INDEX IDX_1D9CA9F75AEA4428 ON hobbie (curriculum_id)');
        $this->addSql('DROP INDEX IDX_D4DB71B5CFE419E2 ON language');
        $this->addSql('ALTER TABLE language CHANGE cv_id curriculum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B55AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('CREATE INDEX IDX_D4DB71B55AEA4428 ON language (curriculum_id)');
        $this->addSql('ALTER TABLE search_profile CHANGE candidat_id candidat_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D755AEA4428');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1035AEA4428');
        $this->addSql('ALTER TABLE hobbie DROP FOREIGN KEY FK_1D9CA9F75AEA4428');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B55AEA4428');
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, skills_id INT NOT NULL, UNIQUE INDEX UNIQ_B66FFE927FF61858 (skills_id), UNIQUE INDEX UNIQ_B66FFE928D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE cv_has_techno (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, techno_id INT DEFAULT NULL, level VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_6A536CD551F3C1BC (techno_id), INDEX IDX_6A536CD5CFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE927FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE928D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cv_has_techno ADD CONSTRAINT FK_6A536CD551F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cv_has_techno ADD CONSTRAINT FK_6A536CD5CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY FK_7BE2A7C38D0EB82');
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY FK_7BE2A7C37FF61858');
        $this->addSql('ALTER TABLE curriculum_has_techno DROP FOREIGN KEY FK_E0DB764D5AEA4428');
        $this->addSql('ALTER TABLE curriculum_has_techno DROP FOREIGN KEY FK_E0DB764D51F3C1BC');
        $this->addSql('DROP TABLE curriculum');
        $this->addSql('DROP TABLE curriculum_has_techno');
        $this->addSql('DROP INDEX IDX_6C3C6D755AEA4428 ON certification');
        $this->addSql('ALTER TABLE certification CHANGE curriculum_id cv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6C3C6D75CFE419E2 ON certification (cv_id)');
        $this->addSql('DROP INDEX IDX_590C1035AEA4428 ON experience');
        $this->addSql('ALTER TABLE experience CHANGE curriculum_id cv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_590C103CFE419E2 ON experience (cv_id)');
        $this->addSql('DROP INDEX IDX_1D9CA9F75AEA4428 ON hobbie');
        $this->addSql('ALTER TABLE hobbie CHANGE curriculum_id cv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hobbie ADD CONSTRAINT FK_1D9CA9F7CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1D9CA9F7CFE419E2 ON hobbie (cv_id)');
        $this->addSql('DROP INDEX IDX_D4DB71B55AEA4428 ON language');
        $this->addSql('ALTER TABLE language CHANGE curriculum_id cv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B5CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D4DB71B5CFE419E2 ON language (cv_id)');
        $this->addSql('ALTER TABLE search_profile CHANGE candidat_id candidat_id INT NOT NULL');
    }
}
