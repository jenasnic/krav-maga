<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240816000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment CHANGE adherent_id adherent_id INT NOT NULL, CHANGE season_id season_id INT NOT NULL');
        $this->addSql('DELETE FROM re_enrollment_token WHERE season_id IS NULL');
        $this->addSql('ALTER TABLE re_enrollment_token CHANGE adherent_id adherent_id INT NOT NULL, CHANGE season_id season_id INT NOT NULL');
        $this->addSql('ALTER TABLE registration CHANGE adherent_id adherent_id INT NOT NULL, CHANGE season_id season_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration CHANGE adherent_id adherent_id INT DEFAULT NULL, CHANGE season_id season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment CHANGE adherent_id adherent_id INT DEFAULT NULL, CHANGE season_id season_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE re_enrollment_token CHANGE adherent_id adherent_id INT DEFAULT NULL, CHANGE season_id season_id INT DEFAULT NULL');
    }
}
