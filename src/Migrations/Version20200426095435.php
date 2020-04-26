<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426095435 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, lien_reservation_id INT DEFAULT NULL, lien_jeu_en_chene_id INT DEFAULT NULL, user_id INT NOT NULL, sujet VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8A8E26E963959AA8 (lien_reservation_id), INDEX IDX_8A8E26E9BBA842A2 (lien_jeu_en_chene_id), INDEX IDX_8A8E26E9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E963959AA8 FOREIGN KEY (lien_reservation_id) REFERENCES reservation_jeu (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9BBA842A2 FOREIGN KEY (lien_jeu_en_chene_id) REFERENCES jeu_en_chene (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE conversation');
    }
}
