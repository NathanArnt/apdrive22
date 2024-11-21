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

    #[ORM\ManyToOne(inversedBy: 'lesDetailsCommandes')]
    private ?Produits $leProduit = null;

    #[ORM\ManyToOne(inversedBy: 'lesDetailsCommandes')]
    private ?Commandes $laCommande = null;

    public function __construct()
    {
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
    public function getLeProduit(): ?Produits
    {
        return $this->leProduit;
    }

    public function setLeProduit(?Produits $leProduit): static
    {
        $this->leProduit = $leProduit;

        return $this;
    }
    public function getLaCommande(): ?Commandes
    {
        return $this->laCommande;
    }

    public function setLaCommande(?Commandes $laCommande): static
    {
        $this->laCommande = $laCommande;

        return $this;
    }
}