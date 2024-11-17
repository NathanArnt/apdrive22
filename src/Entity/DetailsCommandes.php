<?php

namespace App\Entity;

use App\Repository\DetailsCommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailsCommandesRepository::class)]
class DetailsCommandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = 0;

    #[ORM\OneToOne(mappedBy: 'leDetailCommande', cascade: ['persist', 'remove'])]
    private ?Commandes $laCommande = null;

    /**
     * @var Collection<int, Produits>
     */
    #[ORM\ManyToMany(targetEntity: Produits::class, inversedBy: 'lesDetailsCommandes')]
    private Collection $lesProduits;

    public function __construct()
    {
        $this->lesProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getLaCommande(): ?Commandes
    {
        return $this->laCommande;
    }

    public function setLaCommande(?Commandes $laCommande): self
    {
        // unset the owning side of the relation if necessary
        if ($laCommande === null && $this->laCommande !== null) {
            $this->laCommande->setLeDetailCommande(null);
        }

        // set the owning side of the relation if necessary
        if ($laCommande !== null && $laCommande->getLeDetailCommande() !== $this) {
            $laCommande->setLeDetailCommande($this);
        }

        $this->laCommande = $laCommande;

        return $this;
    }

    /**
     * @return Collection<int, Produits>
     */
    public function getLesProduits(): Collection
    {
        return $this->lesProduits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->lesProduits->contains($produit)) {
            $this->lesProduits->add($produit);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        $this->lesProduits->removeElement($produit);

        return $this;
    }

    /**
     * Ajoute un produit ou incrémente la quantité si le produit existe déjà
     */
    public function ajouterProduit(Produits $produit): self
    {
        if ($this->lesProduits->contains($produit)) {
            // Si le produit existe déjà, incrémenter la quantité
            $this->quantite++;
        } else {
            // Si le produit n'existe pas encore, l'ajouter avec une quantité initiale
            $this->addProduit($produit);
            $this->quantite = 1;
        }

        return $this;
    }

    /**
     * Calcule le total du panier en fonction des produits et quantités
     */
    public function calculerTotalPanier(): float
    {
        $total = 0;

        // Calcul du total en multipliant le prix de chaque produit par sa quantité
        foreach ($this->lesProduits as $produit) {
            $total += $produit->getPrix() * $this->quantite;
        }

        return $total;
    }
}
