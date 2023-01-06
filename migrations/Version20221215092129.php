<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215092129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, skills_id INT NOT NULL, UNIQUE INDEX UNIQ_B66FFE928D0EB82 (candidat_id), UNIQUE INDEX UNIQ_B66FFE927FF61858 (skills_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cv_has_techno (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, techno_id INT DEFAULT NULL, level VARCHAR(100) NOT NULL, INDEX IDX_6A536CD5CFE419E2 (cv_id), INDEX IDX_6A536CD551F3C1BC (techno_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skills (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE928D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE927FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id)');
        $this->addSql('ALTER TABLE cv_has_techno ADD CONSTRAINT FK_6A536CD5CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE cv_has_techno ADD CONSTRAINT FK_6A536CD551F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id)');
        $this->addSql('ALTER TABLE candidat_has_techno DROP FOREIGN KEY FK_8D8796EE51F3C1BC');
        $this->addSql('ALTER TABLE candidat_has_techno DROP FOREIGN KEY FK_8D8796EE8D0EB82');
        $this->addSql('DROP TABLE candidat_has_techno');
        $this->addSql('ALTER TABLE candidat DROP cv_file');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D758D0EB82');
        $this->addSql('DROP INDEX IDX_6C3C6D758D0EB82 ON certification');
        $this->addSql('ALTER TABLE certification ADD cv_id INT DEFAULT NULL, DROP candidat_id');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE INDEX IDX_6C3C6D75CFE419E2 ON certification (cv_id)');
        $this->addSql('ALTER TABLE experience ADD cv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE INDEX IDX_590C103CFE419E2 ON experience (cv_id)');
        $this->addSql('ALTER TABLE hard_skill DROP FOREIGN KEY FK_9EE9EE468D0EB82');
        $this->addSql('DROP INDEX IDX_9EE9EE468D0EB82 ON hard_skill');
        $this->addSql('ALTER TABLE hard_skill ADD skills_id INT DEFAULT NULL, DROP candidat_id');
        $this->addSql('ALTER TABLE hard_skill ADD CONSTRAINT FK_9EE9EE467FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id)');
        $this->addSql('CREATE INDEX IDX_9EE9EE467FF61858 ON hard_skill (skills_id)');
        $this->addSql('ALTER TABLE hobbie DROP FOREIGN KEY FK_1D9CA9F78D0EB82');
        $this->addSql('DROP INDEX IDX_1D9CA9F78D0EB82 ON hobbie');
        $this->addSql('ALTER TABLE hobbie ADD cv_id INT DEFAULT NULL, DROP candidat_id');
        $this->addSql('ALTER TABLE hobbie ADD CONSTRAINT FK_1D9CA9F7CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE INDEX IDX_1D9CA9F7CFE419E2 ON hobbie (cv_id)');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B58D0EB82');
        $this->addSql('DROP INDEX IDX_D4DB71B58D0EB82 ON language');
        $this->addSql('ALTER TABLE language ADD cv_id INT DEFAULT NULL, DROP candidat_id');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B5CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE INDEX IDX_D4DB71B5CFE419E2 ON language (cv_id)');
        $this->addSql('ALTER TABLE soft_skill DROP FOREIGN KEY FK_164AECD48D0EB82');
        $this->addSql('DROP INDEX IDX_164AECD48D0EB82 ON soft_skill');
        $this->addSql('ALTER TABLE soft_skill ADD skills_id INT DEFAULT NULL, DROP candidat_id');
        $this->addSql('ALTER TABLE soft_skill ADD CONSTRAINT FK_164AECD47FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id)');
        $this->addSql('CREATE INDEX IDX_164AECD47FF61858 ON soft_skill (skills_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75CFE419E2');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103CFE419E2');
        $this->addSql('ALTER TABLE hobbie DROP FOREIGN KEY FK_1D9CA9F7CFE419E2');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B5CFE419E2');
        $this->addSql('ALTER TABLE hard_skill DROP FOREIGN KEY FK_9EE9EE467FF61858');
        $this->addSql('ALTER TABLE soft_skill DROP FOREIGN KEY FK_164AECD47FF61858');
        $this->addSql('CREATE TABLE candidat_has_techno (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, techno_id INT DEFAULT NULL, level VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8D8796EE8D0EB82 (candidat_id), INDEX IDX_8D8796EE51F3C1BC (techno_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE candidat_has_techno ADD CONSTRAINT FK_8D8796EE51F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE candidat_has_techno ADD CONSTRAINT FK_8D8796EE8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE928D0EB82');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE927FF61858');
        $this->addSql('ALTER TABLE cv_has_techno DROP FOREIGN KEY FK_6A536CD5CFE419E2');
        $this->addSql('ALTER TABLE cv_has_techno DROP FOREIGN KEY FK_6A536CD551F3C1BC');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE cv_has_techno');
        $this->addSql('DROP TABLE skills');
        $this->addSql('ALTER TABLE candidat ADD cv_file VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_6C3C6D75CFE419E2 ON certification');
        $this->addSql('ALTER TABLE certification ADD candidat_id INT NOT NULL, DROP cv_id');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D758D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6C3C6D758D0EB82 ON certification (candidat_id)');
        $this->addSql('DROP INDEX IDX_590C103CFE419E2 ON experience');
        $this->addSql('ALTER TABLE experience DROP cv_id');
        $this->addSql('DROP INDEX IDX_9EE9EE467FF61858 ON hard_skill');
        $this->addSql('ALTER TABLE hard_skill ADD candidat_id INT NOT NULL, DROP skills_id');
        $this->addSql('ALTER TABLE hard_skill ADD CONSTRAINT FK_9EE9EE468D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9EE9EE468D0EB82 ON hard_skill (candidat_id)');
        $this->addSql('DROP INDEX IDX_1D9CA9F7CFE419E2 ON hobbie');
        $this->addSql('ALTER TABLE hobbie ADD candidat_id INT NOT NULL, DROP cv_id');
        $this->addSql('ALTER TABLE hobbie ADD CONSTRAINT FK_1D9CA9F78D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1D9CA9F78D0EB82 ON hobbie (candidat_id)');
        $this->addSql('DROP INDEX IDX_D4DB71B5CFE419E2 ON language');
        $this->addSql('ALTER TABLE language ADD candidat_id INT NOT NULL, DROP cv_id');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B58D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D4DB71B58D0EB82 ON language (candidat_id)');
        $this->addSql('DROP INDEX IDX_164AECD47FF61858 ON soft_skill');
        $this->addSql('ALTER TABLE soft_skill ADD candidat_id INT NOT NULL, DROP skills_id');
        $this->addSql('ALTER TABLE soft_skill ADD CONSTRAINT FK_164AECD48D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_164AECD48D0EB82 ON soft_skill (candidat_id)');
    }
}
