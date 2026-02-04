<?php

namespace App\Entity;

use App\Repository\ContactsRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactsRepository::class)]
#[Gedmo\Loggable]
class Contacts
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
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $mel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $telephone = null;

    #[ORM\ManyToOne(inversedBy: 'fk_contacts')]
    #[Gedmo\Versioned]
    private ?Societes $fk_societes = null;

    public function __construct()
    {
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMel(): ?string
    {
        return $this->mel;
    }

    public function setMel(?string $mel): static
    {
        $this->mel = $mel;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkSocietes' => ['id', 'nom']]])]
    public function getFkSocietes(): ?Societes
    {
        return $this->fk_societes;
    }
    
    public function setFkSocietes(?Societes $fk_societes): static
    {
        $this->fk_societes = $fk_societes;

        return $this;
    }



    public function __toString(): string
    {
        return $this->nom . " " . $this->prenom;
    }
}
