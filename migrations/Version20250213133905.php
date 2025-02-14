<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213133905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE musique (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, artist_id INT NOT NULL, genre VARCHAR(255) NOT NULL, date_sortie DATE NOT NULL, chemin_fichier VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, artist_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE musique_playlist (musique_id INT NOT NULL, playlist_id INT NOT NULL, INDEX IDX_EE1C1A7925E254A1 (musique_id), INDEX IDX_EE1C1A796BBD148 (playlist_id), PRIMARY KEY(musique_id, playlist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist (id INT AUTO_INCREMENT NOT NULL, titre_p VARCHAR(255) NOT NULL, user_id INT NOT NULL, date_creation DATE NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE musique_playlist ADD CONSTRAINT FK_EE1C1A7925E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE musique_playlist ADD CONSTRAINT FK_EE1C1A796BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musique_playlist DROP FOREIGN KEY FK_EE1C1A7925E254A1');
        $this->addSql('ALTER TABLE musique_playlist DROP FOREIGN KEY FK_EE1C1A796BBD148');
        $this->addSql('DROP TABLE musique');
        $this->addSql('DROP TABLE musique_playlist');
        $this->addSql('DROP TABLE playlist');
    }
}
