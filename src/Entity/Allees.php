<?php

namespace App\Entity;

use App\Repository\AlleesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlleesRepository::class)]
class Allees
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Emplacements>
     */
    #[ORM\OneToMany(targetEntity: Emplacements::class, mappedBy: 'laAllee')]
    private Collection $lesEmplacements;

    public function __construct()
    {
        $this->lesEmplacements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Emplacements>
     */
    public function getLesEmplacements(): Collection
    {
        return $this->lesEmplacements;
    }

    public function addLesEmplacement(Emplacements $lesEmplacement): static
    {
        if (!$this->lesEmplacements->contains($lesEmplacement)) {
            $this->lesEmplacements->add($lesEmplacement);
            $lesEmplacement->setLaAllee($this);
        }

        return $this;
    }

    public function removeLesEmplacement(Emplacements $lesEmplacement): static
    {
        if ($this->lesEmplacements->removeElement($lesEmplacement)) {
            // set the owning side to null (unless already changed)
            if ($lesEmplacement->getLaAllee() === $this) {
                $lesEmplacement->setLaAllee(null);
            }
        }

        return $this;
    }
}
