<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209120418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allees (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, le_user_id INT DEFAULT NULL, le_statut_id INT DEFAULT NULL, les_etapes_id INT DEFAULT NULL, INDEX IDX_35D4282C88A1A5E2 (le_user_id), INDEX IDX_35D4282C2F382CF3 (le_statut_id), INDEX IDX_35D4282CAD462D74 (les_etapes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE details_commandes (id INT AUTO_INCREMENT NOT NULL, le_produit_id INT DEFAULT NULL, la_commande_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_4FD424F72C340150 (le_produit_id), INDEX IDX_4FD424F73743EDD (la_commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emplacements (id INT AUTO_INCREMENT NOT NULL, la_allee_id INT DEFAULT NULL, pos_x INT NOT NULL, pos_y INT NOT NULL, INDEX IDX_4F10574749CCFE41 (la_allee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etapes (id INT AUTO_INCREMENT NOT NULL, le_parcours_id INT DEFAULT NULL, INDEX IDX_E3443E172F1FBAC4 (le_parcours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parcours (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, le_stock_id INT DEFAULT NULL, le_emplacement_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_BE2DDF8CC3834AF9 (le_stock_id), UNIQUE INDEX UNIQ_BE2DDF8CA3F94A4A (le_emplacement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C88A1A5E2 FOREIGN KEY (le_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C2F382CF3 FOREIGN KEY (le_statut_id) REFERENCES statut (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CAD462D74 FOREIGN KEY (les_etapes_id) REFERENCES etapes (id)');
        $this->addSql('ALTER TABLE details_commandes ADD CONSTRAINT FK_4FD424F72C340150 FOREIGN KEY (le_produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE details_commandes ADD CONSTRAINT FK_4FD424F73743EDD FOREIGN KEY (la_commande_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE emplacements ADD CONSTRAINT FK_4F10574749CCFE41 FOREIGN KEY (la_allee_id) REFERENCES allees (id)');
        $this->addSql('ALTER TABLE etapes ADD CONSTRAINT FK_E3443E172F1FBAC4 FOREIGN KEY (le_parcours_id) REFERENCES parcours (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC3834AF9 FOREIGN KEY (le_stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CA3F94A4A FOREIGN KEY (le_emplacement_id) REFERENCES emplacements (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C88A1A5E2');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C2F382CF3');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CAD462D74');
        $this->addSql('ALTER TABLE details_commandes DROP FOREIGN KEY FK_4FD424F72C340150');
        $this->addSql('ALTER TABLE details_commandes DROP FOREIGN KEY FK_4FD424F73743EDD');
        $this->addSql('ALTER TABLE emplacements DROP FOREIGN KEY FK_4F10574749CCFE41');
        $this->addSql('ALTER TABLE etapes DROP FOREIGN KEY FK_E3443E172F1FBAC4');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CC3834AF9');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CA3F94A4A');
        $this->addSql('DROP TABLE allees');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE details_commandes');
        $this->addSql('DROP TABLE emplacements');
        $this->addSql('DROP TABLE etapes');
        $this->addSql('DROP TABLE parcours');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
