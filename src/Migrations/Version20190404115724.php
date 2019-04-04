<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404115724 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE repertoire ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C367876727ACA70 FOREIGN KEY (parent_id) REFERENCES repertoire (id)');
        $this->addSql('CREATE INDEX IDX_3C367876727ACA70 ON repertoire (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C367876727ACA70');
        $this->addSql('DROP INDEX IDX_3C367876727ACA70 ON repertoire');
        $this->addSql('ALTER TABLE repertoire DROP parent_id');
    }
}
