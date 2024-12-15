<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209145949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplacements DROP FOREIGN KEY FK_4F10574749CCFE41');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CC3834AF9');
        $this->addSql('DROP TABLE allees');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP INDEX IDX_4F10574749CCFE41 ON emplacements');
        $this->addSql('ALTER TABLE emplacements DROP la_allee_id');
        $this->addSql('DROP INDEX UNIQ_BE2DDF8CC3834AF9 ON produits');
        $this->addSql('ALTER TABLE produits DROP le_stock_id, CHANGE quantite stock INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allees (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produits ADD le_stock_id INT DEFAULT NULL, CHANGE stock quantite INT NOT NULL');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC3834AF9 FOREIGN KEY (le_stock_id) REFERENCES stock (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BE2DDF8CC3834AF9 ON produits (le_stock_id)');
        $this->addSql('ALTER TABLE emplacements ADD la_allee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emplacements ADD CONSTRAINT FK_4F10574749CCFE41 FOREIGN KEY (la_allee_id) REFERENCES allees (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4F10574749CCFE41 ON emplacements (la_allee_id)');
    }
}
