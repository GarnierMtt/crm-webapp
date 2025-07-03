<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
#[Gedmo\Loggable]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Pays = null;

    #[ORM\Column(length: 255)]
    private ?string $commune = null;

    #[ORM\Column]
    private ?int $codePostal = null;

    #[ORM\Column(length: 255)]
    private ?string $nomVoie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroVoie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $complement = null;

    /**
     * @var Collection<int, RelationSocieteAdresse>
     */
    #[ORM\OneToMany(targetEntity: RelationSocieteAdresse::class, mappedBy: 'adresse', orphanRemoval: true)]
    private Collection $Societes;

    public function __construct()
    {
        $this->Societes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }

    public function setPays(string $Pays): static
    {
        $this->Pays = $Pays;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(string $commune): static
    {
        $this->commune = $commune;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getNomVoie(): ?string
    {
        return $this->nomVoie;
    }

    public function setNomVoie(string $nomVoie): static
    {
        $this->nomVoie = $nomVoie;

        return $this;
    }

    public function getNumeroVoie(): ?string
    {
        return $this->numeroVoie;
    }

    public function setNumeroVoie(?string $numeroVoie): static
    {
        $this->numeroVoie = $numeroVoie;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): static
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * @return Collection<int, RelationSocieteAdresse>
     */
    public function getSocietes(): Collection
    {
        return $this->Societes;
    }

    public function addSociete(RelationSocieteAdresse $societe): static
    {
        if (!$this->Societes->contains($societe)) {
            $this->Societes->add($societe);
            $societe->setAdresse($this);
        }

        return $this;
    }

    public function removeSociete(RelationSocieteAdresse $societe): static
    {
        if ($this->Societes->removeElement($societe)) {
            // set the owning side to null (unless already changed)
            if ($societe->getAdresse() === $this) {
                $societe->setAdresse(null);
            }
        }

        return $this;
    }

}
