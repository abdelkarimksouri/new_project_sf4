<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200403170624 extends AbstractMigration
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
        $this->addSql('CREATE TABLE oc_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oc_advert (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, date DATETIME NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, published TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL, nb_applications INT NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B193175989D9B62 (slug), UNIQUE INDEX UNIQ_B1931753DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oc_advert_category (advert_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_435EA006D07ECCB6 (advert_id), INDEX IDX_435EA00612469DE2 (category_id), PRIMARY KEY(advert_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oc_skillAdvert (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, file_path VARCHAR(255) NOT NULL, file_type VARCHAR(100) NOT NULL, file_size INT NOT NULL, is_treated TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, uploaded_at DATETIME NOT NULL, treated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oc_application (id INT AUTO_INCREMENT NOT NULL, advert_id INT NOT NULL, author VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_39F85DD8D07ECCB6 (advert_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, street_number VARCHAR(20) NOT NULL, street_name VARCHAR(255) NOT NULL, street_complementary VARCHAR(255) DEFAULT NULL, zip_code INT NOT NULL, longitude VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, INDEX IDX_D4E6F81F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drug (id INT AUTO_INCREMENT NOT NULL, pharmacy_id INT DEFAULT NULL, drug_name VARCHAR(100) NOT NULL, bare_code VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, expired_at DATETIME NOT NULL, INDEX IDX_43EB7A3E8A94ABE2 (pharmacy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oc_skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1EF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE oc_advert ADD CONSTRAINT FK_B1931753DA5256D FOREIGN KEY (image_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE oc_advert_category ADD CONSTRAINT FK_435EA006D07ECCB6 FOREIGN KEY (advert_id) REFERENCES oc_advert (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oc_advert_category ADD CONSTRAINT FK_435EA00612469DE2 FOREIGN KEY (category_id) REFERENCES oc_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oc_application ADD CONSTRAINT FK_39F85DD8D07ECCB6 FOREIGN KEY (advert_id) REFERENCES oc_advert (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE drug ADD CONSTRAINT FK_43EB7A3E8A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES pharmacy (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE drug DROP FOREIGN KEY FK_43EB7A3E8A94ABE2');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81F92F3E70');
        $this->addSql('ALTER TABLE oc_advert_category DROP FOREIGN KEY FK_435EA00612469DE2');
        $this->addSql('ALTER TABLE oc_advert_category DROP FOREIGN KEY FK_435EA006D07ECCB6');
        $this->addSql('ALTER TABLE oc_application DROP FOREIGN KEY FK_39F85DD8D07ECCB6');
        $this->addSql('ALTER TABLE oc_advert DROP FOREIGN KEY FK_B1931753DA5256D');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1EF5B7AF75');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455F5B7AF75');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1EA76ED395');
        $this->addSql('DROP TABLE pharmacy');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE oc_category');
        $this->addSql('DROP TABLE oc_advert');
        $this->addSql('DROP TABLE oc_advert_category');
        $this->addSql('DROP TABLE oc_skillAdvert');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE oc_application');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE drug');
        $this->addSql('DROP TABLE oc_skill');
        $this->addSql('DROP TABLE user');
    }
}
