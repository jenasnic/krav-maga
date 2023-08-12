<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230808000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('SET FOREIGN_KEY_CHECKS = 0');

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price_option ADD season_id INT NOT NULL');
        $this->addSql('ALTER TABLE price_option ADD CONSTRAINT FK_171FA8E04EC001D1 FOREIGN KEY (season_id) REFERENCES season (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_171FA8E04EC001D1 ON price_option (season_id)');
        $this->addSql('ALTER TABLE registration RENAME COLUMN use_pass15 TO use_pass_citizen, RENAME COLUMN pass15_url TO pass_citizen_url, RENAME COLUMN use_pass50 TO use_pass_sport, RENAME COLUMN pass50_url TO pass_sport_url, ADD re_enrollment TINYINT(1) NOT NULL DEFAULT FALSE');

        $this->addSql('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price_option DROP FOREIGN KEY FK_171FA8E04EC001D1');
        $this->addSql('DROP INDEX IDX_171FA8E04EC001D1 ON price_option');
        $this->addSql('ALTER TABLE price_option DROP season_id');
        $this->addSql('ALTER TABLE registration RENAME COLUMN use_pass_citizen TO use_pass15, RENAME COLUMN pass_citizen_url TO pass15_url, RENAME COLUMN use_pass_sport TO use_pass50, RENAME COLUMN pass_sport_url TO pass50_url, DROP re_enrollment');
    }
}
