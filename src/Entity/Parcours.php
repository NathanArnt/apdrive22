<?php

namespace App\Entity;

use App\Repository\ParcoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcoursRepository::class)]
class Parcours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Etapes>
     */
    #[ORM\OneToMany(targetEntity: Etapes::class, mappedBy: 'leParcours')]
    private Collection $lesEtapes;

    public function __construct()
    {
        $this->lesEtapes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Etapes>
     */
    public function getLesEtapes(): Collection
    {
        return $this->lesEtapes;
    }

    public function addLesEtape(Etapes $lesEtape): static
    {
        if (!$this->lesEtapes->contains($lesEtape)) {
            $this->lesEtapes->add($lesEtape);
            $lesEtape->setLeParcours($this);
        }

        return $this;
    }

    public function removeLesEtape(Etapes $lesEtape): static
    {
        if ($this->lesEtapes->removeElement($lesEtape)) {
            // set the owning side to null (unless already changed)
            if ($lesEtape->getLeParcours() === $this) {
                $lesEtape->setLeParcours(null);
            }
        }

        return $this;
    }
}
