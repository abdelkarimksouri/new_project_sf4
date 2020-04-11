<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200411003627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pharmacy CHANGE address_id address_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE logo_id logo_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE client ADD logo_id INT DEFAULT NULL, ADD email VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, ADD password VARCHAR(255) NOT NULL, CHANGE address_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F98F144A FOREIGN KEY (logo_id) REFERENCES media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455F98F144A ON client (logo_id)');
        $this->addSql('ALTER TABLE oc_advert CHANGE image_id image_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE media CHANGE file_type file_type VARCHAR(100) DEFAULT NULL, CHANGE file_size file_size INT DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL, CHANGE treated_at treated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE address CHANGE country_id country_id INT DEFAULT NULL, CHANGE street_complementary street_complementary VARCHAR(255) DEFAULT NULL, CHANGE longitude longitude VARCHAR(255) DEFAULT NULL, CHANGE latitude latitude VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE city city VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE drug CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address CHANGE country_id country_id INT DEFAULT NULL, CHANGE street_complementary street_complementary VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE longitude longitude VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE latitude latitude VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE city city VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455F98F144A');
        $this->addSql('DROP INDEX UNIQ_C7440455E7927C74 ON client');
        $this->addSql('DROP INDEX UNIQ_C7440455F98F144A ON client');
        $this->addSql('ALTER TABLE client DROP logo_id, DROP email, DROP roles, DROP password, CHANGE address_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE drug CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE media CHANGE file_type file_type VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE file_size file_size INT DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\', CHANGE treated_at treated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE oc_advert CHANGE image_id image_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE pharmacy CHANGE address_id address_id INT DEFAULT NULL, CHANGE logo_id logo_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
