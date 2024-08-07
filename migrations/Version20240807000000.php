<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240807000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD re_enrollment_to_notify TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE re_enrollment_token ADD season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE re_enrollment_token ADD CONSTRAINT FK_6BF1B92F4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('CREATE INDEX IDX_6BF1B92F4EC001D1 ON re_enrollment_token (season_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP re_enrollment_to_notify');
        $this->addSql('ALTER TABLE re_enrollment_token DROP FOREIGN KEY FK_6BF1B92F4EC001D1');
        $this->addSql('DROP INDEX IDX_6BF1B92F4EC001D1 ON re_enrollment_token');
        $this->addSql('ALTER TABLE re_enrollment_token DROP season_id');
    }
}
