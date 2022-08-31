<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, gender VARCHAR(55) NOT NULL, birth_date DATETIME NOT NULL, phone VARCHAR(55) DEFAULT NULL, email VARCHAR(255) NOT NULL, pseudonym VARCHAR(255) DEFAULT NULL, picture_url VARCHAR(255) DEFAULT NULL, address_street VARCHAR(255) DEFAULT NULL, address_zip_code VARCHAR(25) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emergency (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, phone VARCHAR(55) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legal_representative (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, adherent_id INT DEFAULT NULL, season_id INT DEFAULT NULL, date DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, comment LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_6D28840D25F06C53 (adherent_id), INDEX IDX_6D28840D4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_ancv (id INT NOT NULL, number VARCHAR(55) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_cash (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_check (id INT NOT NULL, number VARCHAR(55) NOT NULL, cashing_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_pass (id INT NOT NULL, number VARCHAR(55) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_transfer (id INT NOT NULL, label VARCHAR(55) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_option (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, rank INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purpose (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, rank INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE re_enrollment_token (id VARCHAR(55) NOT NULL, adherent_id INT DEFAULT NULL, expires_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6BF1B92F25F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, legal_representative_id INT DEFAULT NULL, purpose_id INT DEFAULT NULL, price_option_id INT DEFAULT NULL, emergency_id INT DEFAULT NULL, adherent_id INT DEFAULT NULL, season_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, private_note LONGTEXT DEFAULT NULL, licence_number TINYTEXT DEFAULT NULL, licence_date DATETIME DEFAULT NULL, medical_certificate_url VARCHAR(255) DEFAULT NULL, licence_form_url VARCHAR(255) DEFAULT NULL, use_pass15 TINYINT(1) NOT NULL, pass15_url VARCHAR(255) DEFAULT NULL, use_pass50 TINYINT(1) NOT NULL, pass50_url VARCHAR(255) DEFAULT NULL, registered_at DATETIME NOT NULL, copyright_authorization TINYINT(1) NOT NULL, with_legal_representative TINYINT(1) NOT NULL, verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_62A8A7A7E05BB347 (legal_representative_id), INDEX IDX_62A8A7A77FC21131 (purpose_id), INDEX IDX_62A8A7A724752E93 (price_option_id), UNIQUE INDEX UNIQ_62A8A7A7D904784C (emergency_id), UNIQUE INDEX UNIQ_62A8A7A725F06C53 (adherent_id), INDEX IDX_62A8A7A74EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(55) NOT NULL, active TINYINT(1) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(55) NOT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D25F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE payment_ancv ADD CONSTRAINT FK_36A2D249BF396750 FOREIGN KEY (id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_cash ADD CONSTRAINT FK_273A72CDBF396750 FOREIGN KEY (id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_check ADD CONSTRAINT FK_6BC79AA1BF396750 FOREIGN KEY (id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_pass ADD CONSTRAINT FK_EFF34350BF396750 FOREIGN KEY (id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_transfer ADD CONSTRAINT FK_F2E1A8BF396750 FOREIGN KEY (id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE re_enrollment_token ADD CONSTRAINT FK_6BF1B92F25F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7E05BB347 FOREIGN KEY (legal_representative_id) REFERENCES legal_representative (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A77FC21131 FOREIGN KEY (purpose_id) REFERENCES purpose (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A724752E93 FOREIGN KEY (price_option_id) REFERENCES price_option (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7D904784C FOREIGN KEY (emergency_id) REFERENCES emergency (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A725F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A74EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D25F06C53');
        $this->addSql('ALTER TABLE re_enrollment_token DROP FOREIGN KEY FK_6BF1B92F25F06C53');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A725F06C53');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7D904784C');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7E05BB347');
        $this->addSql('ALTER TABLE payment_ancv DROP FOREIGN KEY FK_36A2D249BF396750');
        $this->addSql('ALTER TABLE payment_cash DROP FOREIGN KEY FK_273A72CDBF396750');
        $this->addSql('ALTER TABLE payment_check DROP FOREIGN KEY FK_6BC79AA1BF396750');
        $this->addSql('ALTER TABLE payment_pass DROP FOREIGN KEY FK_EFF34350BF396750');
        $this->addSql('ALTER TABLE payment_transfer DROP FOREIGN KEY FK_F2E1A8BF396750');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A724752E93');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A77FC21131');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D4EC001D1');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A74EC001D1');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE emergency');
        $this->addSql('DROP TABLE legal_representative');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_ancv');
        $this->addSql('DROP TABLE payment_cash');
        $this->addSql('DROP TABLE payment_check');
        $this->addSql('DROP TABLE payment_pass');
        $this->addSql('DROP TABLE payment_transfer');
        $this->addSql('DROP TABLE price_option');
        $this->addSql('DROP TABLE purpose');
        $this->addSql('DROP TABLE re_enrollment_token');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE user');
    }
}
