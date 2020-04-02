<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200402122210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pharmacy (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, user_id INT DEFAULT NULL, generated_name VARCHAR(255) NOT NULL, uid VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, is_night TINYINT(1) NOT NULL, is_holiday TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D6C15C1EF5B7AF75 (address_id), UNIQUE INDEX UNIQ_D6C15C1EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, country_code VARCHAR(20) NOT NULL, country_name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, first_name VARCHAR(200) NOT NULL, last_name VARCHAR(200) NOT NULL, email_address VARCHAR(200) NOT NULL, age INT NOT NULL, gender VARCHAR(20) NOT NULL, phone_number VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C7440455F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, pharmacy_id INT DEFAULT NULL, label VARCHAR(100) NOT NULL, file_path VARCHAR(255) NOT NULL, file_type VARCHAR(100) NOT NULL, file_size INT NOT NULL, is_treated TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, uploaded_at DATETIME NOT NULL, treated_at DATETIME DEFAULT NULL, INDEX IDX_6A2CA10C8A94ABE2 (pharmacy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, street_number VARCHAR(20) NOT NULL, street_name VARCHAR(255) NOT NULL, street_complementary VARCHAR(255) DEFAULT NULL, zip_code INT NOT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, INDEX IDX_D4E6F81F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drug (id INT AUTO_INCREMENT NOT NULL, pharmacy_id INT DEFAULT NULL, drug_name VARCHAR(100) NOT NULL, bare_code VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, expired_at DATETIME NOT NULL, INDEX IDX_43EB7A3E8A94ABE2 (pharmacy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1EF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C8A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES pharmacy (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE drug ADD CONSTRAINT FK_43EB7A3E8A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES pharmacy (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C8A94ABE2');
        $this->addSql('ALTER TABLE drug DROP FOREIGN KEY FK_43EB7A3E8A94ABE2');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81F92F3E70');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1EF5B7AF75');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455F5B7AF75');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1EA76ED395');
        $this->addSql('DROP TABLE pharmacy');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE drug');
        $this->addSql('DROP TABLE user');
    }
}
