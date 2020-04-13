<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413200102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jeu_en_chene_babiole (jeu_en_chene_id INT NOT NULL, babiole_id INT NOT NULL, INDEX IDX_3A71331F5D10B9AE (jeu_en_chene_id), INDEX IDX_3A71331FC1F8BBFC (babiole_id), PRIMARY KEY(jeu_en_chene_id, babiole_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeu_en_chene_babiole ADD CONSTRAINT FK_3A71331F5D10B9AE FOREIGN KEY (jeu_en_chene_id) REFERENCES jeu_en_chene (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_en_chene_babiole ADD CONSTRAINT FK_3A71331FC1F8BBFC FOREIGN KEY (babiole_id) REFERENCES babiole (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE jeu_en_chene_babiole');
    }
}
