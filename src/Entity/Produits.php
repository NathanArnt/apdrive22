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
    private ?int $quantite = null;

    /**
     * @var Collection<int, DetailsCommandes>
     */
    #[ORM\ManyToMany(targetEntity: DetailsCommandes::class, mappedBy: 'lesProduits')]
    private Collection $lesDetailsCommandes;

    #[ORM\OneToOne(inversedBy: 'LeProduit', cascade: ['persist', 'remove'])]
    private ?Stock $leStock = null;

    #[ORM\OneToOne(inversedBy: 'LeProduit', cascade: ['persist', 'remove'])]
    private ?Emplacements $leEmplacement = null;

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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

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
            $lesDetailsCommande->addLesProduit($this);
        }

        return $this;
    }

    public function removeLesDetailsCommande(DetailsCommandes $lesDetailsCommande): static
    {
        if ($this->lesDetailsCommandes->removeElement($lesDetailsCommande)) {
            $lesDetailsCommande->removeLesProduit($this);
        }

        return $this;
    }

    public function getLeStock(): ?Stock
    {
        return $this->leStock;
    }

    public function setLeStock(?Stock $leStock): static
    {
        $this->leStock = $leStock;

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
}
