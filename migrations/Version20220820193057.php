<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220820193057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE requester (id INT AUTO_INCREMENT NOT NULL, blood_group_id_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, INDEX IDX_6C86AEFEF8E0A4F3 (blood_group_id_id), INDEX IDX_6C86AEFE9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE requester ADD CONSTRAINT FK_6C86AEFEF8E0A4F3 FOREIGN KEY (blood_group_id_id) REFERENCES blood_group (id)');
        $this->addSql('ALTER TABLE requester ADD CONSTRAINT FK_6C86AEFE9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE requester');
    }
}
