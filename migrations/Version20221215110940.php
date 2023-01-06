<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215110940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B55AEA4428');
        $this->addSql('DROP INDEX IDX_D4DB71B55AEA4428 ON language');
        $this->addSql('ALTER TABLE language CHANGE curriculum_id skills_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B57FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id)');
        $this->addSql('CREATE INDEX IDX_D4DB71B57FF61858 ON language (skills_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B57FF61858');
        $this->addSql('DROP INDEX IDX_D4DB71B57FF61858 ON language');
        $this->addSql('ALTER TABLE language CHANGE skills_id curriculum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B55AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D4DB71B55AEA4428 ON language (curriculum_id)');
    }
}
