<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207102324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peinture ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE peinture ADD CONSTRAINT FK_8FB3A9D6C54C8C93 FOREIGN KEY (type_id) REFERENCES style (id)');
        $this->addSql('CREATE INDEX IDX_8FB3A9D6C54C8C93 ON peinture (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peinture DROP FOREIGN KEY FK_8FB3A9D6C54C8C93');
        $this->addSql('DROP INDEX IDX_8FB3A9D6C54C8C93 ON peinture');
        $this->addSql('ALTER TABLE peinture DROP type_id');
    }
}
