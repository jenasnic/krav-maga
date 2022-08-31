<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831000001 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO purpose (label, rank) VALUES (\'Découverte\', 1)');
        $this->addSql('INSERT INTO purpose (label, rank) VALUES (\'Remise en forme\', 2)');
        $this->addSql('INSERT INTO purpose (label, rank) VALUES (\'Gagner en confiance en soi\', 3)');
        $this->addSql('INSERT INTO purpose (label, rank) VALUES (\'Apprendre à se défendre\', 4)');
        $this->addSql('INSERT INTO purpose (label, rank) VALUES (\'Passage de ceinture\', 5)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
