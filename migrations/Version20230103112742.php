<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230103112742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoriteOffers (candidat_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_E15AC6A98D0EB82 (candidat_id), INDEX IDX_E15AC6A98805AB2F (annonce_id), PRIMARY KEY(candidat_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoriteOffers ADD CONSTRAINT FK_E15AC6A98D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoriteOffers ADD CONSTRAINT FK_E15AC6A98805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_annonce DROP FOREIGN KEY FK_AF58650F8D0EB82');
        $this->addSql('ALTER TABLE candidat_annonce DROP FOREIGN KEY FK_AF58650F8805AB2F');
        $this->addSql('DROP TABLE candidat_annonce');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidat_annonce (candidat_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_AF58650F8D0EB82 (candidat_id), INDEX IDX_AF58650F8805AB2F (annonce_id), PRIMARY KEY(candidat_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE candidat_annonce ADD CONSTRAINT FK_AF58650F8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_annonce ADD CONSTRAINT FK_AF58650F8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoriteOffers DROP FOREIGN KEY FK_E15AC6A98D0EB82');
        $this->addSql('ALTER TABLE favoriteOffers DROP FOREIGN KEY FK_E15AC6A98805AB2F');
        $this->addSql('DROP TABLE favoriteOffers');
    }
}
