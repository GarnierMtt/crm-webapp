<?php

namespace App\Entity;

use App\Repository\RelationContactAdresseRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: RelationContactAdresseRepository::class)]
#[Gedmo\Loggable]
class RelationContactAdresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Context([AbstractNormalizer::ATTRIBUTES => ['contact' => ['id', 'nom', 'prenom']]])]
    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[Gedmo\Versioned]
    private ?Contact $contact = null;

    #[Context([AbstractNormalizer::ATTRIBUTES => ['adresse' => ['id', 'nomSite']]])]
    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Versioned]
    private ?Adresse $adresse = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $role = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $notes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }



    public function __toString(): string
    {
        return $this->role;
    }
}
