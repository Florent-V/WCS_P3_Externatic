<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106092520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_companies (candidat_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_48543FA88D0EB82 (candidat_id), INDEX IDX_48543FA8979B1AD6 (company_id), PRIMARY KEY(candidat_id, company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_companies ADD CONSTRAINT FK_48543FA88D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_companies ADD CONSTRAINT FK_48543FA8979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce CHANGE title title VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_companies DROP FOREIGN KEY FK_48543FA88D0EB82');
        $this->addSql('ALTER TABLE favorite_companies DROP FOREIGN KEY FK_48543FA8979B1AD6');
        $this->addSql('DROP TABLE favorite_companies');
        $this->addSql('ALTER TABLE annonce CHANGE title title VARCHAR(255) NOT NULL');
    }
}
