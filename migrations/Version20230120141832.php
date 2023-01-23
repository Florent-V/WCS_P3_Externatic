<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230120141832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE search_profile_techno (search_profile_id INT NOT NULL, techno_id INT NOT NULL, INDEX IDX_56A13191144C2272 (search_profile_id), INDEX IDX_56A1319151F3C1BC (techno_id), PRIMARY KEY(search_profile_id, techno_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE search_profile_techno ADD CONSTRAINT FK_56A13191144C2272 FOREIGN KEY (search_profile_id) REFERENCES search_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE search_profile_techno ADD CONSTRAINT FK_56A1319151F3C1BC FOREIGN KEY (techno_id) REFERENCES techno (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_profile_techno DROP FOREIGN KEY FK_56A13191144C2272');
        $this->addSql('ALTER TABLE search_profile_techno DROP FOREIGN KEY FK_56A1319151F3C1BC');
        $this->addSql('DROP TABLE search_profile_techno');
    }
}
