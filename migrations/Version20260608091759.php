<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260608091759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_order (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, date_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, supplier_id INT NOT NULL, INDEX IDX_21E210B22ADD6D8C (supplier_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE purchase_order_line (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, price NUMERIC(10, 2) NOT NULL, purchase_order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_90D6D92BA45D7E6A (purchase_order_id), INDEX IDX_90D6D92B4584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sales_order (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(100) NOT NULL, date_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, customer_id INT NOT NULL, INDEX IDX_36D222E9395C3F3 (customer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sales_order_line (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, price NUMERIC(10, 2) NOT NULL, sales_order_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_93D9398DC023F51C (sales_order_id), INDEX IDX_93D9398D4584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE stock_movement (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, quantity INT NOT NULL, date_at DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, product_id INT NOT NULL, warehouse_id INT NOT NULL, INDEX IDX_BB1BC1B54584665A (product_id), INDEX IDX_BB1BC1B55080ECDE (warehouse_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B22ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE purchase_order_line ADD CONSTRAINT FK_90D6D92BA45D7E6A FOREIGN KEY (purchase_order_id) REFERENCES purchase_order (id)');
        $this->addSql('ALTER TABLE purchase_order_line ADD CONSTRAINT FK_90D6D92B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE sales_order ADD CONSTRAINT FK_36D222E9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE sales_order_line ADD CONSTRAINT FK_93D9398DC023F51C FOREIGN KEY (sales_order_id) REFERENCES sales_order (id)');
        $this->addSql('ALTER TABLE sales_order_line ADD CONSTRAINT FK_93D9398D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B54584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE stock_movement ADD CONSTRAINT FK_BB1BC1B55080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('ALTER TABLE stock ADD quantity INT NOT NULL, ADD product_id INT NOT NULL, ADD warehouse_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656604584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656605080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
        $this->addSql('CREATE INDEX IDX_4B3656604584665A ON stock (product_id)');
        $this->addSql('CREATE INDEX IDX_4B3656605080ECDE ON stock (warehouse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B22ADD6D8C');
        $this->addSql('ALTER TABLE purchase_order_line DROP FOREIGN KEY FK_90D6D92BA45D7E6A');
        $this->addSql('ALTER TABLE purchase_order_line DROP FOREIGN KEY FK_90D6D92B4584665A');
        $this->addSql('ALTER TABLE sales_order DROP FOREIGN KEY FK_36D222E9395C3F3');
        $this->addSql('ALTER TABLE sales_order_line DROP FOREIGN KEY FK_93D9398DC023F51C');
        $this->addSql('ALTER TABLE sales_order_line DROP FOREIGN KEY FK_93D9398D4584665A');
        $this->addSql('ALTER TABLE stock_movement DROP FOREIGN KEY FK_BB1BC1B54584665A');
        $this->addSql('ALTER TABLE stock_movement DROP FOREIGN KEY FK_BB1BC1B55080ECDE');
        $this->addSql('DROP TABLE purchase_order');
        $this->addSql('DROP TABLE purchase_order_line');
        $this->addSql('DROP TABLE sales_order');
        $this->addSql('DROP TABLE sales_order_line');
        $this->addSql('DROP TABLE stock_movement');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656604584665A');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656605080ECDE');
        $this->addSql('DROP INDEX IDX_4B3656604584665A ON stock');
        $this->addSql('DROP INDEX IDX_4B3656605080ECDE ON stock');
        $this->addSql('ALTER TABLE stock DROP quantity, DROP product_id, DROP warehouse_id');
    }
}
