<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230125155654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notif ADD message_id INT DEFAULT NULL, ADD is_in_summary TINYINT(1) NOT NULL, DROP content, CHANGE parameter annonce_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notif ADD CONSTRAINT FK_C0730D6B8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE notif ADD CONSTRAINT FK_C0730D6B537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('CREATE INDEX IDX_C0730D6B8805AB2F ON notif (annonce_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0730D6B537A1329 ON notif (message_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notif DROP FOREIGN KEY FK_C0730D6B8805AB2F');
        $this->addSql('ALTER TABLE notif DROP FOREIGN KEY FK_C0730D6B537A1329');
        $this->addSql('DROP INDEX IDX_C0730D6B8805AB2F ON notif');
        $this->addSql('DROP INDEX UNIQ_C0730D6B537A1329 ON notif');
        $this->addSql('ALTER TABLE notif ADD content JSON NOT NULL, ADD parameter INT DEFAULT NULL, DROP annonce_id, DROP message_id, DROP is_in_summary');
    }
}
