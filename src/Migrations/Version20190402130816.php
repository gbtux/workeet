<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402130816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE partage (id INT AUTO_INCREMENT NOT NULL, document_id INT NOT NULL, utilisateur_id INT DEFAULT NULL, groupe_id INT DEFAULT NULL, type_partage VARCHAR(255) NOT NULL, INDEX IDX_8B929E6EC33F7837 (document_id), INDEX IDX_8B929E6EFB88E14F (utilisateur_id), INDEX IDX_8B929E6E7A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6EC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6E7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE partage');
    }
}
