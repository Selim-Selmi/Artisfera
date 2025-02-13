<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208183401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE musique_playlist (musique_id INT NOT NULL, playlist_id INT NOT NULL, INDEX IDX_EE1C1A7925E254A1 (musique_id), INDEX IDX_EE1C1A796BBD148 (playlist_id), PRIMARY KEY(musique_id, playlist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE musique_playlist ADD CONSTRAINT FK_EE1C1A7925E254A1 FOREIGN KEY (musique_id) REFERENCES musique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE musique_playlist ADD CONSTRAINT FK_EE1C1A796BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musique_playlist DROP FOREIGN KEY FK_EE1C1A7925E254A1');
        $this->addSql('ALTER TABLE musique_playlist DROP FOREIGN KEY FK_EE1C1A796BBD148');
        $this->addSql('DROP TABLE musique_playlist');
    }
}
