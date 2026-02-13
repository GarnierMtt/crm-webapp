<?php

namespace App\Entity;

use App\Repository\TypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypesRepository::class)]
class Types
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Modeles>
     */
    #[ORM\OneToMany(targetEntity: Modeles::class, mappedBy: 'fk_types')]
    private Collection $fk_modeles;

    public function __construct()
    {
        $this->fk_modeles = new ArrayCollection();
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

    /**
     * @return Collection<int, Modeles>
     */
    public function getFkModeles(): Collection
    {
        return $this->fk_modeles;
    }

    public function addFkModele(Modeles $fkModele): static
    {
        if (!$this->fk_modeles->contains($fkModele)) {
            $this->fk_modeles->add($fkModele);
            $fkModele->setFkTypes($this);
        }

        return $this;
    }

    public function removeFkModele(Modeles $fkModele): static
    {
        if ($this->fk_modeles->removeElement($fkModele)) {
            // set the owning side to null (unless already changed)
            if ($fkModele->getFkTypes() === $this) {
                $fkModele->setFkTypes(null);
            }
        }

        return $this;
    }
}
