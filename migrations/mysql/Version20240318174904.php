<?php

declare(strict_types=1);

namespace Migrations\MySql;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318174904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE awssubscription (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', aws_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\', dimension VARCHAR(255) NOT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_7C99369FD91252D3 (aws_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE awssubscription ADD CONSTRAINT FK_7C99369FD91252D3 FOREIGN KEY (aws_id) REFERENCES aws (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE awssubscription DROP FOREIGN KEY FK_7C99369FD91252D3');
        $this->addSql('DROP TABLE awssubscription');
    }
}
