<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318180644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1F26B7B2A76ED3959395C3F3CA9BC19C ON aws');
        $this->addSql('ALTER TABLE aws DROP dimension, DROP quantity');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F26B7B2A76ED3959395C3F3 ON aws (user_id, customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1F26B7B2A76ED3959395C3F3 ON aws');
        $this->addSql('ALTER TABLE aws ADD dimension VARCHAR(255) NOT NULL, ADD quantity INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F26B7B2A76ED3959395C3F3CA9BC19C ON aws (user_id, customer_id, dimension)');
    }
}
