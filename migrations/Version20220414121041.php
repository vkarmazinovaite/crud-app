<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414121041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `groups` (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, title VARCHAR(70) NOT NULL, INDEX IDX_F06D3970166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `projects` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(70) NOT NULL, students_per_group INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `students` (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, in_group_id INT DEFAULT NULL, name VARCHAR(30) NOT NULL, surname VARCHAR(30) NOT NULL, INDEX IDX_A4698DB2166D1F9C (project_id), INDEX IDX_A4698DB2B9ADA51B (in_group_id), UNIQUE INDEX unique_student_idx (name, surname, project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `groups` ADD CONSTRAINT FK_F06D3970166D1F9C FOREIGN KEY (project_id) REFERENCES `projects` (id)');
        $this->addSql('ALTER TABLE `students` ADD CONSTRAINT FK_A4698DB2166D1F9C FOREIGN KEY (project_id) REFERENCES `projects` (id)');
        $this->addSql('ALTER TABLE `students` ADD CONSTRAINT FK_A4698DB2B9ADA51B FOREIGN KEY (in_group_id) REFERENCES `groups` (id)');
    }
}
