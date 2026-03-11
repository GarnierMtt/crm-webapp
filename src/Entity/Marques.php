<?php

namespace App\Entity;

use App\Repository\MarquesRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MarquesRepository::class)]
class Marques
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
    #[ORM\OneToMany(targetEntity: Modeles::class, mappedBy: 'fk_marques')]
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
    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkModeles' => ['id', 'libelle', 'numeroSerie']]])]
    public function getFkModeles(): Collection
    {
        return $this->fk_modeles;
    }

    public function addFkModele(Modeles $fkModele): static
    {
        if (!$this->fk_modeles->contains($fkModele)) {
            $this->fk_modeles->add($fkModele);
            $fkModele->setFkMarques($this);
        }

        return $this;
    }

    public function removeFkModele(Modeles $fkModele): static
    {
        if ($this->fk_modeles->removeElement($fkModele)) {
            // set the owning side to null (unless already changed)
            if ($fkModele->getFkMarques() === $this) {
                $fkModele->setFkMarques(null);
            }
        }

        return $this;
    }
}
