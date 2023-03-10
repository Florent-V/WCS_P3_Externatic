<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124084459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(50) NOT NULL, picture VARCHAR(255) DEFAULT NULL, salary_min INT NOT NULL, contract_type VARCHAR(45) NOT NULL, study_level VARCHAR(45) NOT NULL, remote TINYINT(1) DEFAULT NULL, description LONGTEXT NOT NULL, publication_status INT DEFAULT NULL, ending_at DATE DEFAULT NULL, created_at DATE NOT NULL, optional_info LONGTEXT DEFAULT NULL, salary_max INT DEFAULT NULL, contract_duration VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', work_time VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', INDEX IDX_F65593E5979B1AD6 (company_id), INDEX IDX_F65593E5F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce_techno (annonce_id INT NOT NULL, techno_id INT NOT NULL, INDEX IDX_5920EDC28805AB2F (annonce_id), INDEX IDX_5920EDC251F3C1BC (techno_id), PRIMARY KEY(annonce_id, techno_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointement (id INT AUTO_INCREMENT NOT NULL, recruitment_process_id INT NOT NULL, consultant_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, title VARCHAR(255) NOT NULL, length VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', adress LONGTEXT DEFAULT NULL, INDEX IDX_BD9991CD280DA1F5 (recruitment_process_id), INDEX IDX_BD9991CD44F779A2 (consultant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, age INT DEFAULT NULL, linked_in VARCHAR(255) DEFAULT NULL, github VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(45) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, can_postulate TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_6AB5B471A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_offers (candidat_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_E6F368DD8D0EB82 (candidat_id), INDEX IDX_E6F368DD8805AB2F (annonce_id), PRIMARY KEY(candidat_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_companies (candidat_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_48543FA88D0EB82 (candidat_id), INDEX IDX_48543FA8979B1AD6 (company_id), PRIMARY KEY(candidat_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, curriculum_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, year DATE DEFAULT NULL, link VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_6C3C6D755AEA4428 (curriculum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, externatic_consultant_id INT DEFAULT NULL, siret VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(45) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, phone_number VARCHAR(20) NOT NULL, contact_name VARCHAR(100) NOT NULL, size INT DEFAULT NULL, information LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_4FBF094FA43CB887 (externatic_consultant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curriculum (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_7BE2A7C38D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curriculum_has_techno (id INT AUTO_INCREMENT NOT NULL, curriculum_id INT DEFAULT NULL, techno_id INT DEFAULT NULL, level VARCHAR(100) NOT NULL, INDEX IDX_E0DB764D5AEA4428 (curriculum_id), INDEX IDX_E0DB764D51F3C1BC (techno_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, curriculum_id INT DEFAULT NULL, title VARCHAR(45) NOT NULL, beginning DATE DEFAULT NULL, end DATE DEFAULT NULL, organism VARCHAR(100) DEFAULT NULL, location VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, diploma VARCHAR(100) DEFAULT NULL, is_formation TINYINT(1) DEFAULT NULL, INDEX IDX_590C1035AEA4428 (curriculum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE externatic_consultant (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_54B988D8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hard_skill (id INT AUTO_INCREMENT NOT NULL, skills_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_9EE9EE467FF61858 (skills_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hobbie (id INT AUTO_INCREMENT NOT NULL, curriculum_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, INDEX IDX_1D9CA9F75AEA4428 (curriculum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, skills_id INT DEFAULT NULL, language VARCHAR(45) NOT NULL, level VARCHAR(45) DEFAULT NULL, INDEX IDX_D4DB71B57FF61858 (skills_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, send_to_id INT NOT NULL, send_by_id INT NOT NULL, recruitment_process_id INT DEFAULT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_B6BD307F59574F23 (send_to_id), INDEX IDX_B6BD307FC3852542 (send_by_id), INDEX IDX_B6BD307F280DA1F5 (recruitment_process_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notif (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, content JSON NOT NULL, created_at DATETIME NOT NULL, was_read TINYINT(1) NOT NULL, parameter INT DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_C0730D6BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recruitment_process (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, annonce_id INT DEFAULT NULL, company_id INT DEFAULT NULL, externatic_consultant_id INT NOT NULL, status VARCHAR(45) NOT NULL, information LONGTEXT DEFAULT NULL, rate INT DEFAULT NULL, created_at DATETIME NOT NULL, read_by_consultant TINYINT(1) NOT NULL, read_by_candidat TINYINT(1) NOT NULL, archived_by_candidat TINYINT(1) NOT NULL, archived_by_consultant TINYINT(1) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_A28F88B78D0EB82 (candidat_id), INDEX IDX_A28F88B78805AB2F (annonce_id), INDEX IDX_A28F88B7979B1AD6 (company_id), INDEX IDX_A28F88B7A43CB887 (externatic_consultant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE search_profile (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, search_query JSON NOT NULL, title VARCHAR(255) DEFAULT NULL, work_time TINYINT(1) DEFAULT NULL, period INT DEFAULT NULL, company_id INT DEFAULT NULL, salary_min INT DEFAULT NULL, contract_type VARCHAR(255) DEFAULT NULL, remote TINYINT(1) DEFAULT NULL, INDEX IDX_C8645958D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE search_profile_techno (search_profile_id INT NOT NULL, techno_id INT NOT NULL, INDEX IDX_56A13191144C2272 (search_profile_id), INDEX IDX_56A1319151F3C1BC (techno_id), PRIMARY KEY(search_profile_id, techno_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skills (id INT AUTO_INCREMENT NOT NULL, curriculum_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_D53116705AEA4428 (curriculum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soft_skill (id INT AUTO_INCREMENT NOT NULL, skills_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_164AECD47FF61858 (skills_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE techno (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, picture VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(80) NOT NULL, last_name VARCHAR(80) NOT NULL, is_verified TINYINT(1) NOT NULL, phone VARCHAR(45) NOT NULL, is_active TINYINT(1) NOT NULL, has_notif_unread TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5F675F31B FOREIGN KEY (author_id) REFERENCES externatic_consultant (id)');
        $this->addSql('ALTER TABLE annonce_techno ADD CONSTRAINT FK_5920EDC28805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce_techno ADD CONSTRAINT FK_5920EDC251F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE appointement ADD CONSTRAINT FK_BD9991CD280DA1F5 FOREIGN KEY (recruitment_process_id) REFERENCES recruitment_process (id)');
        $this->addSql('ALTER TABLE appointement ADD CONSTRAINT FK_BD9991CD44F779A2 FOREIGN KEY (consultant_id) REFERENCES externatic_consultant (id)');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B471A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite_offers ADD CONSTRAINT FK_E6F368DD8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_offers ADD CONSTRAINT FK_E6F368DD8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_companies ADD CONSTRAINT FK_48543FA88D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_companies ADD CONSTRAINT FK_48543FA8979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D755AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA43CB887 FOREIGN KEY (externatic_consultant_id) REFERENCES externatic_consultant (id)');
        $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT FK_7BE2A7C38D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE curriculum_has_techno ADD CONSTRAINT FK_E0DB764D5AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('ALTER TABLE curriculum_has_techno ADD CONSTRAINT FK_E0DB764D51F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1035AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('ALTER TABLE externatic_consultant ADD CONSTRAINT FK_54B988D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE hard_skill ADD CONSTRAINT FK_9EE9EE467FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id)');
        $this->addSql('ALTER TABLE hobbie ADD CONSTRAINT FK_1D9CA9F75AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B57FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F59574F23 FOREIGN KEY (send_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC3852542 FOREIGN KEY (send_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F280DA1F5 FOREIGN KEY (recruitment_process_id) REFERENCES recruitment_process (id)');
        $this->addSql('ALTER TABLE notif ADD CONSTRAINT FK_C0730D6BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recruitment_process ADD CONSTRAINT FK_A28F88B78D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE recruitment_process ADD CONSTRAINT FK_A28F88B78805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE recruitment_process ADD CONSTRAINT FK_A28F88B7979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE recruitment_process ADD CONSTRAINT FK_A28F88B7A43CB887 FOREIGN KEY (externatic_consultant_id) REFERENCES externatic_consultant (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE search_profile ADD CONSTRAINT FK_C8645958D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE search_profile_techno ADD CONSTRAINT FK_56A13191144C2272 FOREIGN KEY (search_profile_id) REFERENCES search_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE search_profile_techno ADD CONSTRAINT FK_56A1319151F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D53116705AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('ALTER TABLE soft_skill ADD CONSTRAINT FK_164AECD47FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5979B1AD6');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5F675F31B');
        $this->addSql('ALTER TABLE annonce_techno DROP FOREIGN KEY FK_5920EDC28805AB2F');
        $this->addSql('ALTER TABLE annonce_techno DROP FOREIGN KEY FK_5920EDC251F3C1BC');
        $this->addSql('ALTER TABLE appointement DROP FOREIGN KEY FK_BD9991CD280DA1F5');
        $this->addSql('ALTER TABLE appointement DROP FOREIGN KEY FK_BD9991CD44F779A2');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B471A76ED395');
        $this->addSql('ALTER TABLE favorite_offers DROP FOREIGN KEY FK_E6F368DD8D0EB82');
        $this->addSql('ALTER TABLE favorite_offers DROP FOREIGN KEY FK_E6F368DD8805AB2F');
        $this->addSql('ALTER TABLE favorite_companies DROP FOREIGN KEY FK_48543FA88D0EB82');
        $this->addSql('ALTER TABLE favorite_companies DROP FOREIGN KEY FK_48543FA8979B1AD6');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D755AEA4428');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA43CB887');
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY FK_7BE2A7C38D0EB82');
        $this->addSql('ALTER TABLE curriculum_has_techno DROP FOREIGN KEY FK_E0DB764D5AEA4428');
        $this->addSql('ALTER TABLE curriculum_has_techno DROP FOREIGN KEY FK_E0DB764D51F3C1BC');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1035AEA4428');
        $this->addSql('ALTER TABLE externatic_consultant DROP FOREIGN KEY FK_54B988D8A76ED395');
        $this->addSql('ALTER TABLE hard_skill DROP FOREIGN KEY FK_9EE9EE467FF61858');
        $this->addSql('ALTER TABLE hobbie DROP FOREIGN KEY FK_1D9CA9F75AEA4428');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B57FF61858');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F59574F23');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FC3852542');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F280DA1F5');
        $this->addSql('ALTER TABLE notif DROP FOREIGN KEY FK_C0730D6BA76ED395');
        $this->addSql('ALTER TABLE recruitment_process DROP FOREIGN KEY FK_A28F88B78D0EB82');
        $this->addSql('ALTER TABLE recruitment_process DROP FOREIGN KEY FK_A28F88B78805AB2F');
        $this->addSql('ALTER TABLE recruitment_process DROP FOREIGN KEY FK_A28F88B7979B1AD6');
        $this->addSql('ALTER TABLE recruitment_process DROP FOREIGN KEY FK_A28F88B7A43CB887');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE search_profile DROP FOREIGN KEY FK_C8645958D0EB82');
        $this->addSql('ALTER TABLE search_profile_techno DROP FOREIGN KEY FK_56A13191144C2272');
        $this->addSql('ALTER TABLE search_profile_techno DROP FOREIGN KEY FK_56A1319151F3C1BC');
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D53116705AEA4428');
        $this->addSql('ALTER TABLE soft_skill DROP FOREIGN KEY FK_164AECD47FF61858');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE annonce_techno');
        $this->addSql('DROP TABLE appointement');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE favorite_offers');
        $this->addSql('DROP TABLE favorite_companies');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE curriculum');
        $this->addSql('DROP TABLE curriculum_has_techno');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE externatic_consultant');
        $this->addSql('DROP TABLE hard_skill');
        $this->addSql('DROP TABLE hobbie');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notif');
        $this->addSql('DROP TABLE recruitment_process');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE search_profile');
        $this->addSql('DROP TABLE search_profile_techno');
        $this->addSql('DROP TABLE skills');
        $this->addSql('DROP TABLE soft_skill');
        $this->addSql('DROP TABLE techno');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
