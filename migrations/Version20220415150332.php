<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415150332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2B9ADA51B');
        $this->addSql('DROP INDEX IDX_A4698DB2B9ADA51B ON students');
        $this->addSql('ALTER TABLE students CHANGE in_group_id student_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE students ADD CONSTRAINT FK_A4698DB24DDF95DC FOREIGN KEY (student_group_id) REFERENCES `groups` (id)');
        $this->addSql('CREATE INDEX IDX_A4698DB24DDF95DC ON students (student_group_id)');
    }
}
