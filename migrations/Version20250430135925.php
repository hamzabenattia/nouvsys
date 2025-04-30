<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430135925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offres ADD location_id INT NOT NULL, DROP location');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC354412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC354464D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_C6AC354412469DE2 ON offres (category_id)');
        $this->addSql('CREATE INDEX IDX_C6AC354464D218E ON offres (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC354464D218E');
        $this->addSql('DROP TABLE location');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC354412469DE2');
        $this->addSql('DROP INDEX IDX_C6AC354412469DE2 ON offres');
        $this->addSql('DROP INDEX IDX_C6AC354464D218E ON offres');
        $this->addSql('ALTER TABLE offres ADD location VARCHAR(255) NOT NULL, DROP location_id');
    }
}
