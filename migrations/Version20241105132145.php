<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105132145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emplacements ADD la_allee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emplacements ADD CONSTRAINT FK_4F10574749CCFE41 FOREIGN KEY (la_allee_id) REFERENCES allees (id)');
        $this->addSql('CREATE INDEX IDX_4F10574749CCFE41 ON emplacements (la_allee_id)');
        $this->addSql('ALTER TABLE etapes ADD le_parcours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etapes ADD CONSTRAINT FK_E3443E172F1FBAC4 FOREIGN KEY (le_parcours_id) REFERENCES parcours (id)');
        $this->addSql('CREATE INDEX IDX_E3443E172F1FBAC4 ON etapes (le_parcours_id)');
        $this->addSql('ALTER TABLE produits ADD le_stock_id INT DEFAULT NULL, ADD le_emplacement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC3834AF9 FOREIGN KEY (le_stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CA3F94A4A FOREIGN KEY (le_emplacement_id) REFERENCES emplacements (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BE2DDF8CC3834AF9 ON produits (le_stock_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BE2DDF8CA3F94A4A ON produits (le_emplacement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etapes DROP FOREIGN KEY FK_E3443E172F1FBAC4');
        $this->addSql('DROP INDEX IDX_E3443E172F1FBAC4 ON etapes');
        $this->addSql('ALTER TABLE etapes DROP le_parcours_id');
        $this->addSql('ALTER TABLE emplacements DROP FOREIGN KEY FK_4F10574749CCFE41');
        $this->addSql('DROP INDEX IDX_4F10574749CCFE41 ON emplacements');
        $this->addSql('ALTER TABLE emplacements DROP la_allee_id');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CC3834AF9');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CA3F94A4A');
        $this->addSql('DROP INDEX UNIQ_BE2DDF8CC3834AF9 ON produits');
        $this->addSql('DROP INDEX UNIQ_BE2DDF8CA3F94A4A ON produits');
        $this->addSql('ALTER TABLE produits DROP le_stock_id, DROP le_emplacement_id');
    }
}
