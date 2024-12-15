<?php

namespace App\Entity;

use App\Repository\EmplacementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmplacementsRepository::class)]
class Emplacements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $posX = null;

    #[ORM\Column]
    private ?int $posY = null;

    #[ORM\OneToOne(mappedBy: 'leEmplacement', cascade: ['persist', 'remove'])]
    private ?Produits $LeProduit = null;

    #[ORM\Column]
    private ?bool $statut = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosX(): ?int
    {
        return $this->posX;
    }

    public function setPosX(int $posX): static
    {
        $this->posX = $posX;

        return $this;
    }

    public function getPosY(): ?int
    {
        return $this->posY;
    }

    public function setPosY(int $posY): static
    {
        $this->posY = $posY;

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
            $this->LeProduit->setLeEmplacement(null);
        }

        // set the owning side of the relation if necessary
        if ($LeProduit !== null && $LeProduit->getLeEmplacement() !== $this) {
            $LeProduit->setLeEmplacement($this);
        }

        $this->LeProduit = $LeProduit;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
