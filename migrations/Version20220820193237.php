<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220820193237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE requester DROP FOREIGN KEY FK_6C86AEFEF8E0A4F3');
        $this->addSql('ALTER TABLE requester DROP FOREIGN KEY FK_6C86AEFE9D86650F');
        $this->addSql('DROP INDEX IDX_6C86AEFE9D86650F ON requester');
        $this->addSql('DROP INDEX IDX_6C86AEFEF8E0A4F3 ON requester');
        $this->addSql('ALTER TABLE requester ADD blood_group_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP blood_group_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE requester ADD CONSTRAINT FK_6C86AEFE5F3AECE2 FOREIGN KEY (blood_group_id) REFERENCES blood_group (id)');
        $this->addSql('ALTER TABLE requester ADD CONSTRAINT FK_6C86AEFEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6C86AEFE5F3AECE2 ON requester (blood_group_id)');
        $this->addSql('CREATE INDEX IDX_6C86AEFEA76ED395 ON requester (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE requester DROP FOREIGN KEY FK_6C86AEFE5F3AECE2');
        $this->addSql('ALTER TABLE requester DROP FOREIGN KEY FK_6C86AEFEA76ED395');
        $this->addSql('DROP INDEX IDX_6C86AEFE5F3AECE2 ON requester');
        $this->addSql('DROP INDEX IDX_6C86AEFEA76ED395 ON requester');
        $this->addSql('ALTER TABLE requester ADD blood_group_id_id INT DEFAULT NULL, ADD user_id_id INT DEFAULT NULL, DROP blood_group_id, DROP user_id');
        $this->addSql('ALTER TABLE requester ADD CONSTRAINT FK_6C86AEFEF8E0A4F3 FOREIGN KEY (blood_group_id_id) REFERENCES blood_group (id)');
        $this->addSql('ALTER TABLE requester ADD CONSTRAINT FK_6C86AEFE9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6C86AEFE9D86650F ON requester (user_id_id)');
        $this->addSql('CREATE INDEX IDX_6C86AEFEF8E0A4F3 ON requester (blood_group_id_id)');
    }
}
