<?php

namespace App\Entity;

use App\Repository\LiensFibreRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sites $point_a = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sites $point_b = null;

    /**
     * @var Collection<int, Materiels>
     */
    #[ORM\OneToMany(targetEntity: Materiels::class, mappedBy: 'fk_liensFibre')]
    private Collection $fk_materiels;

    public function __construct()
    {
        $this->fk_materiels = new ArrayCollection();
    }

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

    public function getDateLivraison(): ?string
    {
        return $this->date_livraison ? $this->date_livraison->format('Y-m-d') : null;
    }

    public function setDateLivraison(?\DateTime $date_livraison): static
    {
        $this->date_livraison = $date_livraison;

        return $this;
    }

    public function getDateActivation(): ?string
    {
        return $this->date_activation ? $this->date_activation->format('Y-m-d') : null;
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

    #[Context([AbstractNormalizer::ATTRIBUTES => ['pointA' => ['id', 'nom', 'numeroVoie', 'nomVoie', 'fkCommunes', 'complement']]])]
    public function getPointA(): ?Sites
    {
        return $this->point_a;
    }

    public function setPointA(?Sites $point_a): static
    {
        $this->point_a = $point_a;

        return $this;
    }

    #[Context([AbstractNormalizer::ATTRIBUTES => ['pointB' => ['id', 'nom', 'numeroVoie', 'nomVoie', 'fkCommunes', 'complement']]])]
    public function getPointB(): ?Sites
    {
        return $this->point_b;
    }

    public function setPointB(?Sites $point_b): static
    {
        $this->point_b = $point_b;

        return $this;
    }

    /**
     * @return Collection<int, Materiels>
     */
    public function getFkMateriels(): Collection
    {
        return $this->fk_materiels;
    }

    public function addFkMateriel(Materiels $fkMateriel): static
    {
        if (!$this->fk_materiels->contains($fkMateriel)) {
            $this->fk_materiels->add($fkMateriel);
            $fkMateriel->setFkLiensFibre($this);
        }

        return $this;
    }

    public function removeFkMateriel(Materiels $fkMateriel): static
    {
        if ($this->fk_materiels->removeElement($fkMateriel)) {
            // set the owning side to null (unless already changed)
            if ($fkMateriel->getFkLiensFibre() === $this) {
                $fkMateriel->setFkLiensFibre(null);
            }
        }

        return $this;
    }
}
