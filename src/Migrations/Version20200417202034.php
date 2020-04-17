<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417202034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jeu_en_chene ADD collection_chene_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jeu_en_chene ADD CONSTRAINT FK_33589306995CF855 FOREIGN KEY (collection_chene_id) REFERENCES collection_chene (id)');
        $this->addSql('CREATE INDEX IDX_33589306995CF855 ON jeu_en_chene (collection_chene_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jeu_en_chene DROP FOREIGN KEY FK_33589306995CF855');
        $this->addSql('DROP INDEX IDX_33589306995CF855 ON jeu_en_chene');
        $this->addSql('ALTER TABLE jeu_en_chene DROP collection_chene_id');
    }
}
