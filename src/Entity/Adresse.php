<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
    #[Gedmo\Versioned]
    private ?string $pays = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $commune = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    private ?int $codePostal = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $nomVoie = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $numeroVoie = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $complement = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $nomSite = null;

    #[Context([AbstractNormalizer::ATTRIBUTES => ['societe' => ['id', 'nom']]])]
    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Versioned]
    private ?Societe $societe = null;

    /**
     * @var Collection<int, RelationContactAdresse>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['contacts' => ['contact', 'role', 'notes']]])]
    #[ORM\OneToMany(targetEntity: RelationContactAdresse::class, mappedBy: 'adresse', orphanRemoval: true)]
    private Collection $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

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

    public function getNomSite(): ?string
    {
        return $this->nomSite;
    }

    public function setNomSite(string $nomSite): static
    {
        $this->nomSite = $nomSite;

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * @return Collection<int, RelationContactAdresse>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(RelationContactAdresse $relationContactAdresse): static
    {
        if (!$this->contacts->contains($relationContactAdresse)) {
            $this->contacts->add($relationContactAdresse);
            $relationContactAdresse->setAdresse($this);
        }

        return $this;
    }

    public function removeContact(RelationContactAdresse $relationContactAdresse): static
    {
        if ($this->contacts->removeElement($relationContactAdresse)) {
            // set the owning side to null (unless already changed)
            if ($relationContactAdresse->getAdresse() === $this) {
                $relationContactAdresse->setAdresse(null);
            }
        }

        return $this;
    }



    public function __toString(): string
    {
        return $this->nomSite;
    }
}
