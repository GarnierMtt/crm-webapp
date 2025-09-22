<?php

namespace App\Entity;

use App\Repository\LienFibreRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: LienFibreRepository::class)]
#[Gedmo\Loggable]
class LienFibre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Context([AbstractNormalizer::ATTRIBUTES => ['adresses' => ['id', 'nomSite']]])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Versioned]
    private ?Adresse $pointA = null;

    #[Context([AbstractNormalizer::ATTRIBUTES => ['adresses' => ['id', 'nomSite']]])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Gedmo\Versioned]
    private ?Adresse $pointB = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Gedmo\Versioned]
    private ?int $nombreFibres = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    private ?int $distance = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Versioned]
    private ?float $attenuation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $referenceFibre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $referenceOperateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $referenceLiaison = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $dateLivraison = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $dateActivation = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    private ?bool $lienActive = null;

    #[Context([AbstractNormalizer::ATTRIBUTES => ['projet' => ['id', 'nom']]])]
    #[ORM\ManyToOne(inversedBy: 'lienFibres')]
    #[Gedmo\Versioned]
    private ?Projet $projet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointA(): ?Adresse
    {
        return $this->pointA;
    }

    public function setPointA(?Adresse $pointA): static
    {
        $this->pointA = $pointA;

        return $this;
    }

    public function getPointB(): ?Adresse
    {
        return $this->pointB;
    }

    public function setPointB(?Adresse $pointB): static
    {
        $this->pointB = $pointB;

        return $this;
    }

    public function getNombreFibres(): ?int
    {
        return $this->nombreFibres;
    }

    public function setNombreFibres(int $nombreFibres): static
    {
        $this->nombreFibres = $nombreFibres;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getAttenuation(): ?float
    {
        return $this->attenuation;
    }

    public function setAttenuation(?float $attenuation): static
    {
        $this->attenuation = $attenuation;

        return $this;
    }

    public function getReferenceFibre(): ?string
    {
        return $this->referenceFibre;
    }

    public function setReferenceFibre(?string $referenceFibre): static
    {
        $this->referenceFibre = $referenceFibre;

        return $this;
    }

    public function getReferenceOperateur(): ?string
    {
        return $this->referenceOperateur;
    }

    public function setReferenceOperateur(?string $referenceOperateur): static
    {
        $this->referenceOperateur = $referenceOperateur;

        return $this;
    }

    public function getReferenceLiaison(): ?string
    {
        return $this->referenceLiaison;
    }

    public function setReferenceLiaison(?string $referenceLiaison): static
    {
        $this->referenceLiaison = $referenceLiaison;

        return $this;
    }

    public function getDateLivraison(): ?\DateTime
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTime $dateLivraison): static
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getDateActivation(): ?\DateTime
    {
        return $this->dateActivation;
    }

    public function setDateActivation(?\DateTime $dateActivation): static
    {
        $this->dateActivation = $dateActivation;

        return $this;
    }

    public function isLienActive(): ?bool
    {
        return $this->lienActive;
    }

    public function setLienActive(bool $lienActive): static
    {
        $this->lienActive = $lienActive;

        return $this;
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
}
