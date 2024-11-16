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
    private ?int $quantite = null;

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

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getLaCommande(): ?Commandes
    {
        return $this->laCommande;
    }

    public function setLaCommande(?Commandes $laCommande): static
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

    /**
     * Ajoute un produit à la commande
     * Si le produit existe déjà, on met à jour la quantité
     */
    public function ajouterProduit(Produits $produit): self
    {
        // Si le produit n'est pas déjà dans la commande, on l'ajoute
        if (!$this->lesProduits->contains($produit)) {
            $this->lesProduits->add($produit);
        }

        // Vous pouvez ici gérer la quantité si vous en avez besoin (par exemple, augmenter la quantité d'un produit si il est déjà dans le panier)
        $this->quantite += 1; // exemple : chaque produit ajouté à la commande augmente la quantité

        return $this;
    }

    public function removeLesProduit(Produits $lesProduit): static
    {
        $this->lesProduits->removeElement($lesProduit);

        return $this;
    }

    public function calculerTotalPanier(): float
    {
        $total = 0;

        foreach ($this->lesProduits as $produit) {
            $total += $produit->getPrix(); // Calcule le total en fonction des prix des produits
        }

        return $total;
    }
}
