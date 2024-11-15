<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105131853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE details_commandes_produits (details_commandes_id INT NOT NULL, produits_id INT NOT NULL, INDEX IDX_BEC4C7FCA69C5741 (details_commandes_id), INDEX IDX_BEC4C7FCCD11A2CF (produits_id), PRIMARY KEY(details_commandes_id, produits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE details_commandes_produits ADD CONSTRAINT FK_BEC4C7FCA69C5741 FOREIGN KEY (details_commandes_id) REFERENCES details_commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE details_commandes_produits ADD CONSTRAINT FK_BEC4C7FCCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes ADD le_user_id INT DEFAULT NULL, ADD le_statut_id INT DEFAULT NULL, ADD les_etapes_id INT DEFAULT NULL, ADD le_detail_commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C88A1A5E2 FOREIGN KEY (le_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C2F382CF3 FOREIGN KEY (le_statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CAD462D74 FOREIGN KEY (les_etapes_id) REFERENCES etapes (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C86874DC9 FOREIGN KEY (le_detail_commande_id) REFERENCES details_commandes (id)');
        $this->addSql('CREATE INDEX IDX_35D4282C88A1A5E2 ON commandes (le_user_id)');
        $this->addSql('CREATE INDEX IDX_35D4282C2F382CF3 ON commandes (le_statut_id)');
        $this->addSql('CREATE INDEX IDX_35D4282CAD462D74 ON commandes (les_etapes_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_35D4282C86874DC9 ON commandes (le_detail_commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE details_commandes_produits DROP FOREIGN KEY FK_BEC4C7FCA69C5741');
        $this->addSql('ALTER TABLE details_commandes_produits DROP FOREIGN KEY FK_BEC4C7FCCD11A2CF');
        $this->addSql('DROP TABLE details_commandes_produits');
        $this->addSql('DROP TABLE stock');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C88A1A5E2');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C2F382CF3');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CAD462D74');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C86874DC9');
        $this->addSql('DROP INDEX IDX_35D4282C88A1A5E2 ON commandes');
        $this->addSql('DROP INDEX IDX_35D4282C2F382CF3 ON commandes');
        $this->addSql('DROP INDEX IDX_35D4282CAD462D74 ON commandes');
        $this->addSql('DROP INDEX UNIQ_35D4282C86874DC9 ON commandes');
        $this->addSql('ALTER TABLE commandes DROP le_user_id, DROP le_statut_id, DROP les_etapes_id, DROP le_detail_commande_id');
    }
}
