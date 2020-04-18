<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200418201619 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE babiole ADD type_babiole_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE babiole ADD CONSTRAINT FK_502AFF88DCFB6DD9 FOREIGN KEY (type_babiole_id) REFERENCES type_babiole (id)');
        $this->addSql('CREATE INDEX IDX_502AFF88DCFB6DD9 ON babiole (type_babiole_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE babiole DROP FOREIGN KEY FK_502AFF88DCFB6DD9');
        $this->addSql('DROP INDEX IDX_502AFF88DCFB6DD9 ON babiole');
        $this->addSql('ALTER TABLE babiole DROP type_babiole_id');
    }
}
