<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214144905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, consultant_id INT NOT NULL, title VARCHAR(255) NOT NULL, picture VARCHAR(255) DEFAULT NULL, salary VARCHAR(255) NOT NULL, contract_type VARCHAR(45) NOT NULL, study_level VARCHAR(45) NOT NULL, remote TINYINT(1) DEFAULT NULL, description LONGTEXT NOT NULL, work_time VARCHAR(45) DEFAULT NULL, publication_status VARCHAR(45) DEFAULT NULL, ending_at DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, INDEX IDX_F65593E5979B1AD6 (company_id), INDEX IDX_F65593E544F779A2 (consultant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce_candidat (annonce_id INT NOT NULL, candidat_id INT NOT NULL, INDEX IDX_27FDBAB38805AB2F (annonce_id), INDEX IDX_27FDBAB38D0EB82 (candidat_id), PRIMARY KEY(annonce_id, candidat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce_techno (annonce_id INT NOT NULL, techno_id INT NOT NULL, INDEX IDX_5920EDC28805AB2F (annonce_id), INDEX IDX_5920EDC251F3C1BC (techno_id), PRIMARY KEY(annonce_id, techno_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointement (id INT AUTO_INCREMENT NOT NULL, recruitment_process_id INT NOT NULL, date DATE NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_BD9991CD280DA1F5 (recruitment_process_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidat_has_techno (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, techno_id INT DEFAULT NULL, level VARCHAR(50) DEFAULT NULL, INDEX IDX_8D8796EE8D0EB82 (candidat_id), INDEX IDX_8D8796EE51F3C1BC (techno_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, title VARCHAR(100) NOT NULL, year DATE DEFAULT NULL, link VARCHAR(100) DEFAULT NULL, INDEX IDX_6C3C6D758D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, title VARCHAR(45) NOT NULL, beginning DATE DEFAULT NULL, end DATE DEFAULT NULL, organism VARCHAR(100) DEFAULT NULL, location VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, diploma VARCHAR(100) DEFAULT NULL, is_formation TINYINT(1) DEFAULT NULL, INDEX IDX_590C1038D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hard_skill (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_9EE9EE468D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobbie (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, title VARCHAR(100) NOT NULL, INDEX IDX_1D9CA9F78D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, language VARCHAR(45) NOT NULL, level VARCHAR(45) DEFAULT NULL, INDEX IDX_D4DB71B58D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, send_to_id INT NOT NULL, send_by_id INT NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_B6BD307F59574F23 (send_to_id), INDEX IDX_B6BD307FC3852542 (send_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recrutement_process (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, annonce_id INT NOT NULL, status VARCHAR(45) NOT NULL, information LONGTEXT NOT NULL, rate INT DEFAULT NULL, createdAt DATETIME NOT NULL, INDEX IDX_1A56BBDF8D0EB82 (candidat_id), INDEX IDX_1A56BBDF8805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE search_profile (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, salary_max VARCHAR(45) DEFAULT NULL, salary_min VARCHAR(45) DEFAULT NULL, work_time VARCHAR(100) DEFAULT NULL, title VARCHAR(100) DEFAULT NULL, is_remote TINYINT(1) DEFAULT NULL, INDEX IDX_C8645958D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soft_skill (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_164AECD48D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE techno (id INT AUTO_INCREMENT NOT NULL, search_profile_id INT DEFAULT NULL, name VARCHAR(45) NOT NULL, picture VARCHAR(255) NOT NULL, INDEX IDX_3987EEDC144C2272 (search_profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E544F779A2 FOREIGN KEY (consultant_id) REFERENCES externatic_consultant (id)');
        $this->addSql('ALTER TABLE annonce_candidat ADD CONSTRAINT FK_27FDBAB38805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_candidat ADD CONSTRAINT FK_27FDBAB38D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_techno ADD CONSTRAINT FK_5920EDC28805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_techno ADD CONSTRAINT FK_5920EDC251F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointement ADD CONSTRAINT FK_BD9991CD280DA1F5 FOREIGN KEY (recruitment_process_id) REFERENCES recrutement_process (id)');
        $this->addSql('ALTER TABLE candidat_has_techno ADD CONSTRAINT FK_8D8796EE8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE candidat_has_techno ADD CONSTRAINT FK_8D8796EE51F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D758D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1038D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE hard_skill ADD CONSTRAINT FK_9EE9EE468D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE hobbie ADD CONSTRAINT FK_1D9CA9F78D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B58D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F59574F23 FOREIGN KEY (send_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC3852542 FOREIGN KEY (send_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recrutement_process ADD CONSTRAINT FK_1A56BBDF8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE recrutement_process ADD CONSTRAINT FK_1A56BBDF8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE search_profile ADD CONSTRAINT FK_C8645958D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE soft_skill ADD CONSTRAINT FK_164AECD48D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE techno ADD CONSTRAINT FK_3987EEDC144C2272 FOREIGN KEY (search_profile_id) REFERENCES search_profile (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5979B1AD6');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E544F779A2');
        $this->addSql('ALTER TABLE annonce_candidat DROP FOREIGN KEY FK_27FDBAB38805AB2F');
        $this->addSql('ALTER TABLE annonce_candidat DROP FOREIGN KEY FK_27FDBAB38D0EB82');
        $this->addSql('ALTER TABLE annonce_techno DROP FOREIGN KEY FK_5920EDC28805AB2F');
        $this->addSql('ALTER TABLE annonce_techno DROP FOREIGN KEY FK_5920EDC251F3C1BC');
        $this->addSql('ALTER TABLE appointement DROP FOREIGN KEY FK_BD9991CD280DA1F5');
        $this->addSql('ALTER TABLE candidat_has_techno DROP FOREIGN KEY FK_8D8796EE8D0EB82');
        $this->addSql('ALTER TABLE candidat_has_techno DROP FOREIGN KEY FK_8D8796EE51F3C1BC');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D758D0EB82');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1038D0EB82');
        $this->addSql('ALTER TABLE hard_skill DROP FOREIGN KEY FK_9EE9EE468D0EB82');
        $this->addSql('ALTER TABLE hobbie DROP FOREIGN KEY FK_1D9CA9F78D0EB82');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B58D0EB82');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F59574F23');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FC3852542');
        $this->addSql('ALTER TABLE recrutement_process DROP FOREIGN KEY FK_1A56BBDF8D0EB82');
        $this->addSql('ALTER TABLE recrutement_process DROP FOREIGN KEY FK_1A56BBDF8805AB2F');
        $this->addSql('ALTER TABLE search_profile DROP FOREIGN KEY FK_C8645958D0EB82');
        $this->addSql('ALTER TABLE soft_skill DROP FOREIGN KEY FK_164AECD48D0EB82');
        $this->addSql('ALTER TABLE techno DROP FOREIGN KEY FK_3987EEDC144C2272');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE annonce_candidat');
        $this->addSql('DROP TABLE annonce_techno');
        $this->addSql('DROP TABLE appointement');
        $this->addSql('DROP TABLE candidat_has_techno');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE hard_skill');
        $this->addSql('DROP TABLE hobbie');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE recrutement_process');
        $this->addSql('DROP TABLE search_profile');
        $this->addSql('DROP TABLE soft_skill');
        $this->addSql('DROP TABLE techno');
    }
}
