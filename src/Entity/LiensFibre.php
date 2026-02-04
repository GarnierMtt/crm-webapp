<?php

namespace App\Entity;

use App\Repository\LiensFibreRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LiensFibreRepository::class)]
#[Gedmo\Loggable]
class LiensFibre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Gedmo\Versioned]
    private ?int $nombre_fibres = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    private ?int $distance = null;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Versioned]
    private ?float $attenuation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $reference_fibre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $reference_operateur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $reference_liaison = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $date_livraison = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $date_activation = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    private ?bool $active = null;

    #[ORM\ManyToOne(inversedBy: 'fk_liens_fibre')]
    #[Gedmo\Versioned]
    private ?Projets $fk_projets = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreFibres(): ?int
    {
        return $this->nombre_fibres;
    }

    public function setNombreFibres(int $nombre_fibres): static
    {
        $this->nombre_fibres = $nombre_fibres;

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
        return $this->reference_fibre;
    }

    public function setReferenceFibre(?string $reference_fibre): static
    {
        $this->reference_fibre = $reference_fibre;

        return $this;
    }

    public function getReferenceOperateur(): ?string
    {
        return $this->reference_operateur;
    }

    public function setReferenceOperateur(?string $reference_operateur): static
    {
        $this->reference_operateur = $reference_operateur;

        return $this;
    }

    public function getReferenceLiaison(): ?string
    {
        return $this->reference_liaison;
    }

    public function setReferenceLiaison(?string $reference_liaison): static
    {
        $this->reference_liaison = $reference_liaison;

        return $this;
    }

    public function getDateLivraison(): ?\DateTime
    {
        return $this->date_livraison;
    }

    public function setDateLivraison(?\DateTime $date_livraison): static
    {
        $this->date_livraison = $date_livraison;

        return $this;
    }

    public function getDateActivation(): ?\DateTime
    {
        return $this->date_activation;
    }

    public function setDateActivation(?\DateTime $date_activation): static
    {
        $this->date_activation = $date_activation;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkProjets' => ['id', 'nom']]])]
    public function getFkProjets(): ?Projets
    {
        return $this->fk_projets;
    }

    public function setFkProjets(?Projets $fk_projets): static
    {
        $this->fk_projets = $fk_projets;

        return $this;
    }
}
