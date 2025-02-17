<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217114925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collection_t (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, user_id INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE textile (id INT AUTO_INCREMENT NOT NULL, collection_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, matiere VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, dimension VARCHAR(255) NOT NULL, createur VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, technique VARCHAR(255) NOT NULL, INDEX IDX_431B68B9514956FD (collection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE textile ADD CONSTRAINT FK_431B68B9514956FD FOREIGN KEY (collection_id) REFERENCES collection_t (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE textile DROP FOREIGN KEY FK_431B68B9514956FD');
        $this->addSql('DROP TABLE collection_t');
        $this->addSql('DROP TABLE textile');
    }
}
