<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210504170025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenoms VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participer ADD participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F89D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id)');
        $this->addSql('CREATE INDEX IDX_EDBE16F89D1C3019 ON participer (participant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F89D1C3019');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP INDEX IDX_EDBE16F89D1C3019 ON participer');
        $this->addSql('ALTER TABLE participer DROP participant_id');
    }
}
