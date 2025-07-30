<?php

namespace App\Entity;

use App\Repository\RelationProjetSocieteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: RelationProjetSocieteRepository::class)]
#[Gedmo\Loggable]
class RelationProjetSociete
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'societes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Gedmo\Versioned]
    private ?Projet $projet = null;

    #[ORM\ManyToOne(inversedBy: 'Projets')]
    #[Gedmo\Versioned]
    private ?Societe $societe = null;

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

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): static
    {
        $this->projet = $projet;

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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }


    public function __toString(): string
    {
        return $this->role;
    }
}
