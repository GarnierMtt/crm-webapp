<?php

namespace App\Entity;

use App\Repository\MaterielsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielsRepository::class)]
class Materiels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'fk_materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Modeles $fk_modeles = null;

    #[ORM\ManyToOne(inversedBy: 'fk_materiels')]
    private ?Projets $fk_projets = null;

    #[ORM\ManyToOne(inversedBy: 'fk_materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sites $fk_sites = null;

    #[ORM\ManyToOne(inversedBy: 'fk_materiels')]
    private ?LiensFibre $fk_liensFibre = null;

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

    public function getFkModeles(): ?Modeles
    {
        return $this->fk_modeles;
    }

    public function setFkModeles(?Modeles $fk_modeles): static
    {
        $this->fk_modeles = $fk_modeles;

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

    public function getFkSites(): ?Sites
    {
        return $this->fk_sites;
    }

    public function setFkSites(?Sites $fk_sites): static
    {
        $this->fk_sites = $fk_sites;

        return $this;
    }

    public function getFkLiensFibre(): ?LiensFibre
    {
        return $this->fk_liensFibre;
    }

    public function setFkLiensFibre(?LiensFibre $fk_liensFibre): static
    {
        $this->fk_liensFibre = $fk_liensFibre;

        return $this;
    }
}
