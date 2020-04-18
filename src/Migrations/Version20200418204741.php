<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200418204741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categorie_babiole (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, num INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE babiole ADD categorie_babiole_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE babiole ADD CONSTRAINT FK_502AFF885F317150 FOREIGN KEY (categorie_babiole_id) REFERENCES categorie_babiole (id)');
        $this->addSql('CREATE INDEX IDX_502AFF885F317150 ON babiole (categorie_babiole_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE babiole DROP FOREIGN KEY FK_502AFF885F317150');
        $this->addSql('DROP TABLE categorie_babiole');
        $this->addSql('DROP INDEX IDX_502AFF885F317150 ON babiole');
        $this->addSql('ALTER TABLE babiole DROP categorie_babiole_id');
    }
}
