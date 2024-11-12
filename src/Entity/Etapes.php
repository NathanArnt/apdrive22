<?php

namespace App\Entity;

use App\Repository\EtapesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtapesRepository::class)]
class Etapes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Commandes>
     */
    #[ORM\OneToMany(targetEntity: Commandes::class, mappedBy: 'lesEtapes')]
    private Collection $laCommande;

    #[ORM\ManyToOne(inversedBy: 'lesEtapes')]
    private ?Parcours $leParcours = null;

    public function __construct()
    {
        $this->laCommande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getLaCommande(): Collection
    {
        return $this->laCommande;
    }

    public function addLaCommande(Commandes $laCommande): static
    {
        if (!$this->laCommande->contains($laCommande)) {
            $this->laCommande->add($laCommande);
            $laCommande->setLesEtapes($this);
        }

        return $this;
    }

    public function removeLaCommande(Commandes $laCommande): static
    {
        if ($this->laCommande->removeElement($laCommande)) {
            // set the owning side to null (unless already changed)
            if ($laCommande->getLesEtapes() === $this) {
                $laCommande->setLesEtapes(null);
            }
        }

        return $this;
    }

    public function getLeParcours(): ?Parcours
    {
        return $this->leParcours;
    }

    public function setLeParcours(?Parcours $leParcours): static
    {
        $this->leParcours = $leParcours;

        return $this;
    }
}
