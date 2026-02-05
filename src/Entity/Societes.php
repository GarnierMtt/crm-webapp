<?php

namespace App\Entity;

use App\Repository\SocietesRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SocietesRepository::class)]
#[Gedmo\Loggable]
class Societes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $telephone_standard = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $siret = null;

    /**
     * @var Collection<int, Contacts>
     */
    #[ORM\OneToMany(targetEntity: Contacts::class, mappedBy: 'fk_societes')]
    private Collection $fk_contacts;

    public function __construct()
    {
        $this->fk_contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephoneStandard(): ?string
    {
        return $this->telephone_standard;
    }

    public function setTelephoneStandard(?string $telephone_standard): static
    {
        $this->telephone_standard = $telephone_standard;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection<int, Contacts>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkContacts' => ['id', 'nom', 'prenom', 'mel', 'telephone']]])]
    public function getFkContacts(): Collection
    {
        return $this->fk_contacts;
    }

    public function addFkContacts(Contacts $fk_contacts): static
    {
        if (!$this->fk_contacts->contains($fk_contacts)) {
            $this->fk_contacts->add($fk_contacts);
            $fk_contacts->setFkSocietes($this);
        }

        return $this;
    }

    public function removeFkContacts(Contacts $fk_contacts): static
    {
        if ($this->fk_contacts->removeElement($fk_contacts)) {
            // set the owning side to null (unless already changed)
            if ($fk_contacts->getFkSocietes() === $this) {
                $fk_contacts->setFkSocietes(null);
            }
        }

        return $this;
    }



    public function __toString(): string
    {
        return $this->nom;
    }
}
