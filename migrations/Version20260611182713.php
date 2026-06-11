<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260611182713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY `FK_23A0E66A45D7E6A`');
        $this->addSql('DROP INDEX IDX_23A0E66A45D7E6A ON article');
        $this->addSql('ALTER TABLE article DROP purchase_order_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE birthday birthday DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE article ADD purchase_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT `FK_23A0E66A45D7E6A` FOREIGN KEY (purchase_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66A45D7E6A ON article (purchase_order_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE birthday birthday DATE DEFAULT \'NULL\'');
    }
}
