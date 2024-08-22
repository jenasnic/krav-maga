<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240822000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration ADD registration_type VARCHAR(55) NOT NULL');
        $this->addSql('UPDATE registration SET registration_type = \'ADULT\' WHERE with_legal_representative = 0');
        $this->addSql('UPDATE registration SET registration_type = \'MINOR\' WHERE with_legal_representative = 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration DROP registration_type');
    }
}
