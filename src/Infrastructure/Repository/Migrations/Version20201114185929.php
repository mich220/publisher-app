<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201114185929 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS publisher_db');

        $this->addSql('CREATE TABLE publisher_db.post (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR (80) NOT NULL,
            content VARCHAR (6000) NOT NULL,
            access_key VARCHAR (16),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE publisher_db.comment (
            id INT AUTO_INCREMENT NOT NULL,
            content VARCHAR (6000) NOT NULL,
            post_id INT NOT NULL,
            access_key VARCHAR (16),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE publisher_db.comment 
            ADD CONSTRAINT FK_PostComment 
            FOREIGN KEY (post_id) 
            REFERENCES publisher_db.post (id)
            ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE publisher_db.comment DROP FOREIGN KEY FK_PostComment');
        $this->addSql('DROP TABLE publisher_db.post');
        $this->addSql('DROP TABLE publisher_db.comment');
    }
}
