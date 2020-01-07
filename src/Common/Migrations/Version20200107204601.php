<?php

declare(strict_types=1);

namespace App\Common\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200107204601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("grant all on mailserver.* to 'mailadmin'@'localhost' identified by '".getenv('MAILADMIN_PASSWORD')."';");
        $this->addSql("grant all on mailserver.* to 'mailserver'@'127.0.0.1' identified by '".getenv('MAILSERVER_PASSWORD')."';");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
