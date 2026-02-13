<?php

namespace App\Entity;

use App\Repository\TachesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TachesRepository::class)]
class Taches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_fin = null;

    /**
     * @var Collection<int, Utilisateurs>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateurs::class, inversedBy: 'fk_taches')]
    private Collection $fk_utilisateurs;

    #[ORM\ManyToOne(inversedBy: 'fk_taches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projets $fk_projets = null;

    #[ORM\ManyToOne(inversedBy: 'fk_taches')]
    private ?Societes $fk_societes = null;

    public function __construct()
    {
        $this->fk_utilisateurs = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTime $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTime $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateurs>
     */
    public function getFkUtilisateurs(): Collection
    {
        return $this->fk_utilisateurs;
    }

    public function addFkUtilisateur(Utilisateurs $fkUtilisateur): static
    {
        if (!$this->fk_utilisateurs->contains($fkUtilisateur)) {
            $this->fk_utilisateurs->add($fkUtilisateur);
        }

        return $this;
    }

    public function removeFkUtilisateur(Utilisateurs $fkUtilisateur): static
    {
        $this->fk_utilisateurs->removeElement($fkUtilisateur);

        return $this;
    }

    public function getFkProjets(): ?Projets
    {
        return $this->fk_projets;
    }

    public function setFkProjets(?Projets $fk_projets): static
    {
        $this->fk_projets = $fk_projets;

        return $this;
    }

    public function getFkSocietes(): ?Societes
    {
        return $this->fk_societes;
    }

    public function setFkSocietes(?Societes $fk_societes): static
    {
        $this->fk_societes = $fk_societes;

        return $this;
    }
}
