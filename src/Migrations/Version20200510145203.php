<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510145203 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE actualite (id INT AUTO_INCREMENT NOT NULL, theme_id INT DEFAULT NULL, jeu_en_chene_id INT DEFAULT NULL, collection_chene_id INT DEFAULT NULL, babiole_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, date DATE NOT NULL, texte LONGTEXT NOT NULL, prioritaire TINYINT(1) DEFAULT \'0\' NOT NULL, image_name VARCHAR(255) DEFAULT NULL, INDEX IDX_5492819759027487 (theme_id), INDEX IDX_549281975D10B9AE (jeu_en_chene_id), INDEX IDX_54928197995CF855 (collection_chene_id), INDEX IDX_54928197C1F8BBFC (babiole_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_5492819759027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_549281975D10B9AE FOREIGN KEY (jeu_en_chene_id) REFERENCES jeu_en_chene (id)');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_54928197995CF855 FOREIGN KEY (collection_chene_id) REFERENCES collection_chene (id)');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_54928197C1F8BBFC FOREIGN KEY (babiole_id) REFERENCES babiole (id)');
        $this->addSql('ALTER TABLE niveau CHANGE nom_cache nom_cache TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE actualite');
        $this->addSql('ALTER TABLE niveau CHANGE nom_cache nom_cache TINYINT(1) NOT NULL');
    }
}
