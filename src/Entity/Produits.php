<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\OneToOne(inversedBy: 'LeProduit', cascade: ['persist', 'remove'])]
    private ?Emplacements $leEmplacement = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, DetailsCommandes>
     */
    #[ORM\OneToMany(targetEntity: DetailsCommandes::class, mappedBy: 'leProduit')]
    private Collection $lesDetailsCommandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->lesDetailsCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getLeEmplacement(): ?Emplacements
    {
        return $this->leEmplacement;
    }

    public function setLeEmplacement(?Emplacements $leEmplacement): static
    {
        $this->leEmplacement = $leEmplacement;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommandes>
     */
    public function getLesDetailsCommandes(): Collection
    {
        return $this->lesDetailsCommandes;
    }

    public function addLesDetailsCommande(DetailsCommandes $lesDetailsCommande): static
    {
        if (!$this->lesDetailsCommandes->contains($lesDetailsCommande)) {
            $this->lesDetailsCommandes->add($lesDetailsCommande);
            $lesDetailsCommande->setLeProduit($this);
        }

        return $this;
    }

    public function removeLesDetailsCommande(DetailsCommandes $lesDetailsCommande): static
    {
        if ($this->lesDetailsCommandes->removeElement($lesDetailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($lesDetailsCommande->getLeProduit() === $this) {
                $lesDetailsCommande->setLeProduit(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
