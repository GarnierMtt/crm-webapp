<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
#[Gedmo\Loggable]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Communes>
     */
    #[ORM\OneToMany(targetEntity: Communes::class, mappedBy: 'fk_pays')]
    private Collection $fk_communes;

    public function __construct()
    {
        $this->fk_communes = new ArrayCollection();
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
     * @return Collection<int, Communes>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkCommunes' => ['id', 'libelle']]])]
    public function getFkCommunes(): Collection
    {
        return $this->fk_communes;
    }

    public function addFkCommune(Communes $fkCommune): static
    {
        if (!$this->fk_communes->contains($fkCommune)) {
            $this->fk_communes->add($fkCommune);
            $fkCommune->setFkPays($this);
        }

        return $this;
    }

    public function removeFkCommune(Communes $fkCommune): static
    {
        if ($this->fk_communes->removeElement($fkCommune)) {
            // set the owning side to null (unless already changed)
            if ($fkCommune->getFkPays() === $this) {
                $fkCommune->setFkPays(null);
            }
        }

        return $this;
    }
}
