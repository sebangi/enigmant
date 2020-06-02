<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602190630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE babiole DROP FOREIGN KEY FK_502AFF88C46F70B5');
        $this->addSql('DROP INDEX IDX_502AFF88C46F70B5 ON babiole');
        $this->addSql('ALTER TABLE babiole DROP reservation_jeu_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE babiole ADD reservation_jeu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE babiole ADD CONSTRAINT FK_502AFF88C46F70B5 FOREIGN KEY (reservation_jeu_id) REFERENCES reservation_jeu (id)');
        $this->addSql('CREATE INDEX IDX_502AFF88C46F70B5 ON babiole (reservation_jeu_id)');
    }
}
