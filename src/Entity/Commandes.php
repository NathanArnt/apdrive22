<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lesCommandes')]
    private ?User $leUser = null;

    #[ORM\ManyToOne(inversedBy: 'lesCommandes')]
    private ?Statut $leStatut = null;

    #[ORM\ManyToOne(inversedBy: 'laCommande')]
    private ?Etapes $lesEtapes = null;

    /**
     * @var Collection<int, DetailsCommandes>
     */
    #[ORM\OneToMany(targetEntity: DetailsCommandes::class, mappedBy: 'laCommande')]
    private Collection $lesDetailsCommandes;

    public function __construct()
    {
        $this->lesDetailsCommandes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeUser(): ?User
    {
        return $this->leUser;
    }

    public function setLeUser(?User $leUser): static
    {
        $this->leUser = $leUser;

        return $this;
    }

    public function getLeStatut(): ?Statut
    {
        return $this->leStatut;
    }

    public function setLeStatut(?Statut $leStatut): static
    {
        $this->leStatut = $leStatut;

        return $this;
    }

    public function getLesEtapes(): ?Etapes
    {
        return $this->lesEtapes;
    }

    public function setLesEtapes(?Etapes $lesEtapes): static
    {
        $this->lesEtapes = $lesEtapes;

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommandes>
     */
    public function getLesDetailsCommandes(): Collection
    {
        return $this->lesDetailsCommandes;
    }

    public function addLesDetailsCommande(DetailsCommandes $lesDetailsCommande): static
    {
        if (!$this->lesDetailsCommandes->contains($lesDetailsCommande)) {
            $this->lesDetailsCommandes->add($lesDetailsCommande);
            $lesDetailsCommande->setLaCommande($this);
        }

        return $this;
    }

    public function removeLesDetailsCommande(DetailsCommandes $lesDetailsCommande): static
    {
        if ($this->lesDetailsCommandes->removeElement($lesDetailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($lesDetailsCommande->getLaCommande() === $this) {
                $lesDetailsCommande->setLaCommande(null);
            }
        }

        return $this;
    }

    // Ajoute un produit au panier
    public function ajouterProduit(Produits $produit, EntityManagerInterface $entityManager): void
    {
        $detailsCommande = null;
        
        // Vérifier si le produit est déjà dans le panier
        foreach ($this->lesDetailsCommandes as $detail) {
            if ($detail->getLeProduit() === $produit) {
                $detailsCommande = $detail;
                break;
            }
        }

        if (!$detailsCommande) {
            // Si le produit n'est pas déjà dans le panier, l'ajouter
            $detailsCommande = new DetailsCommandes();
            $detailsCommande->setLaCommande($this);
            $detailsCommande->setLeProduit($produit);
            $detailsCommande->setQuantite(1);  // Quantité par défaut
            $entityManager->persist($detailsCommande);
        } else {
            // Sinon, incrémenter la quantité
            $detailsCommande->setQuantite($detailsCommande->getQuantite() + 1);
        }

        $entityManager->flush();
    }

    // Retire un produit du panier
    public function retirerProduit(Produits $produit, EntityManagerInterface $entityManager): void
{
    foreach ($this->lesDetailsCommandes as $detail) {
        if ($detail->getLeProduit() === $produit) {
            $quantite = $detail->getQuantite();
            if ($quantite > 1) {
                $detail->setQuantite($quantite - 1);
            } else {
                $entityManager->remove($detail);
            }
            $entityManager->flush();
            return;
        }
    }
}
    public function calculerTotal(): float
    {
        $total = 0.0;

        // Parcourir les détails de commande
        foreach ($this->getLesDetailsCommandes() as $detailsCommande) {
            $produit = $detailsCommande->getLeProduit();
            if ($produit) {
                $total += $produit->getPrix() * $detailsCommande->getQuantite();
            }
        }

        return $total;
    }
}
