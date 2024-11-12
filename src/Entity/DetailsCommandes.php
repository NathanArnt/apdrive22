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

    public function addLesProduit(Produits $lesProduit): static
    {
        if (!$this->lesProduits->contains($lesProduit)) {
            $this->lesProduits->add($lesProduit);
        }

        return $this;
    }

    public function removeLesProduit(Produits $lesProduit): static
    {
        $this->lesProduits->removeElement($lesProduit);

        return $this;
    }
}
