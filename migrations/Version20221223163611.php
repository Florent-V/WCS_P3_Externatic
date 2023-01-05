<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223163611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY FK_7BE2A7C37FF61858');
        $this->addSql('DROP INDEX UNIQ_7BE2A7C37FF61858 ON curriculum');
        $this->addSql('ALTER TABLE curriculum DROP skills_id');
        $this->addSql('ALTER TABLE skills ADD curriculum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D53116705AEA4428 FOREIGN KEY (curriculum_id) REFERENCES curriculum (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D53116705AEA4428 ON skills (curriculum_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curriculum ADD skills_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT FK_7BE2A7C37FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7BE2A7C37FF61858 ON curriculum (skills_id)');
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D53116705AEA4428');
        $this->addSql('DROP INDEX UNIQ_D53116705AEA4428 ON skills');
        $this->addSql('ALTER TABLE skills DROP curriculum_id');
    }
}
