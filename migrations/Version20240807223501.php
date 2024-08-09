<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240807223501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shopping_cart_item (id INT AUTO_INCREMENT NOT NULL, shopping_cart_id_id INT NOT NULL, item_id_id INT NOT NULL, INDEX IDX_E59A1DF43F611409 (shopping_cart_id_id), INDEX IDX_E59A1DF455E38587 (item_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF43F611409 FOREIGN KEY (shopping_cart_id_id) REFERENCES shopping_cart (id)');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF455E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart_item DROP FOREIGN KEY FK_E59A1DF43F611409');
        $this->addSql('ALTER TABLE shopping_cart_item DROP FOREIGN KEY FK_E59A1DF455E38587');
        $this->addSql('DROP TABLE shopping_cart_item');
    }
}
