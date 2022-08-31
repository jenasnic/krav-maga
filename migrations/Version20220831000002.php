<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831000002 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO price_option (label, amount, rank) VALUES (\'Full access\', 220, 1)');
        $this->addSql('INSERT INTO price_option (label, amount, rank) VALUES (\'KMix-MMA Defense & Krav Maga Self Defense\', 180, 2)');
        $this->addSql('INSERT INTO price_option (label, amount, rank) VALUES (\'Cardio Fit Krav & Krav Defense Training (Defense Femmes inclus)\', 180, 3)');
        $this->addSql('INSERT INTO price_option (label, amount, rank) VALUES (\'Cardio Fit Krav\', 120, 4)');
        $this->addSql('INSERT INTO price_option (label, amount, rank) VALUES (\'Defense Ados 11-15 ans (KMix-MMA Defense inclus)\', 120, 5)');
        $this->addSql('INSERT INTO price_option (label, amount, rank) VALUES (\'Defense Femmes\', 80, 6)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
