<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130150625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notif ADD appointment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notif ADD CONSTRAINT FK_C0730D6BE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointement (id)');
        $this->addSql('CREATE INDEX IDX_C0730D6BE5B533F9 ON notif (appointment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notif DROP FOREIGN KEY FK_C0730D6BE5B533F9');
        $this->addSql('DROP INDEX IDX_C0730D6BE5B533F9 ON notif');
        $this->addSql('ALTER TABLE notif DROP appointment_id');
    }
}
