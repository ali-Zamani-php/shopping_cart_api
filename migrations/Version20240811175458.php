<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811175458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shopping_cart_item (id INT AUTO_INCREMENT NOT NULL, shopping_cart_id INT DEFAULT NULL, item_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_E59A1DF445F80CD (shopping_cart_id), INDEX IDX_E59A1DF4126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF445F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id)');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF4126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_shopping_cart DROP FOREIGN KEY FK_9E067C2D126F525E');
        $this->addSql('ALTER TABLE item_shopping_cart DROP FOREIGN KEY FK_9E067C2D45F80CD');
        $this->addSql('DROP TABLE item_shopping_cart');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_shopping_cart (item_id INT NOT NULL, shopping_cart_id INT NOT NULL, INDEX IDX_9E067C2D126F525E (item_id), INDEX IDX_9E067C2D45F80CD (shopping_cart_id), PRIMARY KEY(item_id, shopping_cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item_shopping_cart ADD CONSTRAINT FK_9E067C2D126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_shopping_cart ADD CONSTRAINT FK_9E067C2D45F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shopping_cart_item DROP FOREIGN KEY FK_E59A1DF445F80CD');
        $this->addSql('ALTER TABLE shopping_cart_item DROP FOREIGN KEY FK_E59A1DF4126F525E');
        $this->addSql('DROP TABLE shopping_cart_item');
    }
}
