<?php

declare(strict_types=1);

namespace App\Common\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200104142122 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE virtual_users (id VARCHAR(64) NOT NULL, domain_id VARCHAR(64) DEFAULT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(150) NOT NULL, quota INT NOT NULL, INDEX IDX_3C68956A115F0EE5 (domain_id), UNIQUE INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE virtual_aliases (id VARCHAR(64) NOT NULL, domain_id VARCHAR(64) DEFAULT NULL, source VARCHAR(100) NOT NULL, destination VARCHAR(100) NOT NULL, INDEX IDX_696568F6115F0EE5 (domain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE virtual_domains (id VARCHAR(64) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_admin (id VARCHAR(64) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(150) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE virtual_users ADD CONSTRAINT FK_3C68956A115F0EE5 FOREIGN KEY (domain_id) REFERENCES virtual_domains (id)');
        $this->addSql('ALTER TABLE virtual_aliases ADD CONSTRAINT FK_696568F6115F0EE5 FOREIGN KEY (domain_id) REFERENCES virtual_domains (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE virtual_users DROP FOREIGN KEY FK_3C68956A115F0EE5');
        $this->addSql('ALTER TABLE virtual_aliases DROP FOREIGN KEY FK_696568F6115F0EE5');
        $this->addSql('DROP TABLE virtual_users');
        $this->addSql('DROP TABLE virtual_aliases');
        $this->addSql('DROP TABLE virtual_domains');
        $this->addSql('DROP TABLE api_admin');
    }
}
