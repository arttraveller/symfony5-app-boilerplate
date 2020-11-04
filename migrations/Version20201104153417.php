<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201104153417 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE users ADD reset_token_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD reset_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX users_unique_reset_token ON users (reset_token_token)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX users_unique_reset_token');
        $this->addSql('ALTER TABLE users DROP reset_token_token');
        $this->addSql('ALTER TABLE users DROP reset_token_expires');
    }
}
