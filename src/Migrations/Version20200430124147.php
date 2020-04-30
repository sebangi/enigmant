<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430124147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE theme ADD route VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE en_cours_lecture en_cours_lecture TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE en_cours_lecture_gourou en_cours_lecture_gourou TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message CHANGE en_cours_lecture en_cours_lecture TINYINT(1) NOT NULL, CHANGE en_cours_lecture_gourou en_cours_lecture_gourou TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE theme DROP route');
    }
}
