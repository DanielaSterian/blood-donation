<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220820172545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD blood_group_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495F3AECE2 FOREIGN KEY (blood_group_id) REFERENCES blood_group (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6495F3AECE2 ON user (blood_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495F3AECE2');
        $this->addSql('DROP INDEX IDX_8D93D6495F3AECE2 ON user');
        $this->addSql('ALTER TABLE user DROP blood_group_id, CHANGE roles roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
