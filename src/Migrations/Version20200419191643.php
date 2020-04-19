<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200419191643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reservation_jeu (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, jeu_id INT NOT NULL, date_demande DATE NOT NULL, date_retrait DATE DEFAULT NULL, date_fin_prevue DATE DEFAULT NULL, avis_public LONGTEXT DEFAULT NULL, avis_prive_difficulte LONGTEXT DEFAULT NULL, avis_prive_technique LONGTEXT DEFAULT NULL, reussi TINYINT(1) DEFAULT \'0\' NOT NULL, temps_jeu_estime INT DEFAULT NULL, INDEX IDX_D7E6A06AA76ED395 (user_id), INDEX IDX_D7E6A06A8C9E392E (jeu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation_jeu ADD CONSTRAINT FK_D7E6A06AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_jeu ADD CONSTRAINT FK_D7E6A06A8C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu_en_chene (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE reservation_jeu');
    }
}
