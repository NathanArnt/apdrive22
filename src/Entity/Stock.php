<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\OneToOne(mappedBy: 'leStock', cascade: ['persist', 'remove'])]
    private ?Produits $LeProduit = null;

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

    public function getLeProduit(): ?Produits
    {
        return $this->LeProduit;
    }

    public function setLeProduit(?Produits $LeProduit): static
    {
        // unset the owning side of the relation if necessary
        if ($LeProduit === null && $this->LeProduit !== null) {
            $this->LeProduit->setLeStock(null);
        }

        // set the owning side of the relation if necessary
        if ($LeProduit !== null && $LeProduit->getLeStock() !== $this) {
            $LeProduit->setLeStock($this);
        }

        $this->LeProduit = $LeProduit;

        return $this;
    }
}
