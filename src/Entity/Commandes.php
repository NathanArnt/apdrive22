<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
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

    #[ORM\OneToOne(inversedBy: 'laCommande', cascade: ['persist', 'remove'])]
    private ?DetailsCommandes $leDetailCommande = null;

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

    public function getLeDetailCommande(): ?DetailsCommandes
    {
        return $this->leDetailCommande;
    }

    public function setLeDetailCommande(?DetailsCommandes $leDetailCommande): static
    {
        $this->leDetailCommande = $leDetailCommande;

        return $this;
    }
}
