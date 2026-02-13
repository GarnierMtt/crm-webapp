<?php

namespace App\Entity;

use App\Repository\ModelesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelesRepository::class)]
class Modeles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero_serie = null;

    #[ORM\ManyToOne(inversedBy: 'fk_modeles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Marques $fk_marques = null;

    #[ORM\ManyToOne(inversedBy: 'fk_modeles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Types $fk_types = null;

    /**
     * @var Collection<int, Materiels>
     */
    #[ORM\OneToMany(targetEntity: Materiels::class, mappedBy: 'fk_modeles')]
    private Collection $fk_materiels;

    public function __construct()
    {
        $this->fk_materiels = new ArrayCollection();
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

    public function getNumeroSerie(): ?string
    {
        return $this->numero_serie;
    }

    public function setNumeroSerie(?string $numero_serie): static
    {
        $this->numero_serie = $numero_serie;

        return $this;
    }

    public function getFkMarques(): ?Marques
    {
        return $this->fk_marques;
    }

    public function setFkMarques(?Marques $fk_marques): static
    {
        $this->fk_marques = $fk_marques;

        return $this;
    }

    public function getFkTypes(): ?Types
    {
        return $this->fk_types;
    }

    public function setFkTypes(?Types $fk_types): static
    {
        $this->fk_types = $fk_types;

        return $this;
    }

    /**
     * @return Collection<int, Materiels>
     */
    public function getFkMateriels(): Collection
    {
        return $this->fk_materiels;
    }

    public function addFkMateriel(Materiels $fkMateriel): static
    {
        if (!$this->fk_materiels->contains($fkMateriel)) {
            $this->fk_materiels->add($fkMateriel);
            $fkMateriel->setFkModeles($this);
        }

        return $this;
    }

    public function removeFkMateriel(Materiels $fkMateriel): static
    {
        if ($this->fk_materiels->removeElement($fkMateriel)) {
            // set the owning side to null (unless already changed)
            if ($fkMateriel->getFkModeles() === $this) {
                $fkMateriel->setFkModeles(null);
            }
        }

        return $this;
    }
}
