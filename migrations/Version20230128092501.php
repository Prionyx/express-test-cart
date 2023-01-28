<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128092501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE manufacturer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE manufacturer (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX manufacturer_unique_name ON manufacturer (name)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, product_model_id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(12, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04ADB2C5DD70 ON product (product_model_id)');
        $this->addSql('CREATE UNIQUE INDEX product_unique_name ON product (name, product_model_id)');
        $this->addSql('CREATE TABLE product_model (id INT NOT NULL, manufacturer_id INT NOT NULL, product_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_76C90985A23B42D ON product_model (manufacturer_id)');
        $this->addSql('CREATE INDEX IDX_76C9098514959723 ON product_model (product_type_id)');
        $this->addSql('CREATE UNIQUE INDEX product_model_unique_name ON product_model (name, manufacturer_id, product_type_id)');
        $this->addSql('CREATE TABLE product_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX product_type_unique_name ON product_type (name)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB2C5DD70 FOREIGN KEY (product_model_id) REFERENCES product_model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_model ADD CONSTRAINT FK_76C90985A23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_model ADD CONSTRAINT FK_76C9098514959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE manufacturer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_model_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_type_id_seq CASCADE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADB2C5DD70');
        $this->addSql('ALTER TABLE product_model DROP CONSTRAINT FK_76C90985A23B42D');
        $this->addSql('ALTER TABLE product_model DROP CONSTRAINT FK_76C9098514959723');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_model');
        $this->addSql('DROP TABLE product_type');
    }
}
