<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161222172843 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:UserId)\', role VARCHAR(255) DEFAULT NULL, password VARCHAR(250) NOT NULL COMMENT \'password\', email VARCHAR(250) NOT NULL COMMENT \'E-mail address (should be unique)\', deleted TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'Flag for marking user as deleted\', created_at DATETIME NOT NULL COMMENT \'Timestamp of user registration (UTC)\', deleted_at DATETIME DEFAULT NULL COMMENT \'Timestamp of user deletion (UTC)\', first_name VARCHAR(250) DEFAULT NULL COMMENT \'User first name\', last_name VARCHAR(250) DEFAULT NULL COMMENT \'User last name\', session_token VARCHAR(250) DEFAULT NULL COMMENT \'User active session token\', UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E957698A6A (role), INDEX IDX_1483A5E9844A19ED (session_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E957698A6A FOREIGN KEY (role) REFERENCES roles (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E957698A6A');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE roles');
    }
}
