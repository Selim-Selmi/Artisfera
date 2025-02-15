<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212092748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE nbParticipant nb_participant INT NOT NULL');
        $this->addSql('ALTER TABLE sponsor CHANGE telephone telephone VARCHAR(9) NOT NULL, CHANGE siteWeb site_web VARCHAR(255) DEFAULT NULL, CHANGE dateAjout date_ajout DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE nb_participant nbParticipant INT NOT NULL');
        $this->addSql('ALTER TABLE sponsor CHANGE telephone telephone VARCHAR(20) NOT NULL, CHANGE site_web siteWeb VARCHAR(255) DEFAULT NULL, CHANGE date_ajout dateAjout DATETIME NOT NULL');
    }
}
