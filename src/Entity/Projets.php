<?php

namespace App\Entity;

use App\Repository\ProjetsRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ProjetsRepository::class)]
//#[Gedmo\Tree(type: 'nested')]
#[Gedmo\Loggable]
class Projets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $date_fin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $description = null;

    /**
     * @var Collection<int, LiensFibre>
     */
    #[ORM\OneToMany(targetEntity: LiensFibre::class, mappedBy: 'fk_projets')]
    private Collection $fk_liens_fibre;

    #[ORM\ManyToOne(inversedBy: 'fk_projets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Societes $societe_client = null;

    /**
     * @var Collection<int, Taches>
     */
    #[ORM\OneToMany(targetEntity: Taches::class, mappedBy: 'fk_projets', orphanRemoval: true)]
    private Collection $fk_taches;

    /**
     * @var Collection<int, Materiels>
     */
    #[ORM\OneToMany(targetEntity: Materiels::class, mappedBy: 'fk_projets')]
    private Collection $fk_materiels;

    public function __construct()
    {
        //$this->children = new ArrayCollection();
        $this->fk_liens_fibre = new ArrayCollection();
        $this->fk_taches = new ArrayCollection();
        $this->fk_materiels = new ArrayCollection();
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
     * @return Collection<int, LiensFibre>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkLiensFibre' => ['id']]])]
    public function getFkLiensFibre(): Collection
    {
        return $this->fk_liens_fibre;
    }

    public function addFkLiensFibre(LiensFibre $fkLiensFibre): static
    {
        if (!$this->fk_liens_fibre->contains($fkLiensFibre)) {
            $this->fk_liens_fibre->add($fkLiensFibre);
            $fkLiensFibre->setFkProjets($this);
        }

        return $this;
    }

    public function removeFkLiensFibre(LiensFibre $fkLiensFibre): static
    {
        if ($this->fk_liens_fibre->removeElement($fkLiensFibre)) {
            // set the owning side to null (unless already changed)
            if ($fkLiensFibre->getFkProjets() === $this) {
                $fkLiensFibre->setFkProjets(null);
            }
        }

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

    public function getSocieteClient(): ?Societes
    {
        return $this->societe_client;
    }

    public function setSocieteClient(?Societes $societe_client): static
    {
        $this->societe_client = $societe_client;

        return $this;
    }

    /**
     * @return Collection<int, Taches>
     */
    public function getFkTaches(): Collection
    {
        return $this->fk_taches;
    }

    public function addFkTach(Taches $fkTach): static
    {
        if (!$this->fk_taches->contains($fkTach)) {
            $this->fk_taches->add($fkTach);
            $fkTach->setFkProjets($this);
        }

        return $this;
    }

    public function removeFkTach(Taches $fkTach): static
    {
        if ($this->fk_taches->removeElement($fkTach)) {
            // set the owning side to null (unless already changed)
            if ($fkTach->getFkProjets() === $this) {
                $fkTach->setFkProjets(null);
            }
        }

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
            $fkMateriel->setFkProjets($this);
        }

        return $this;
    }

    public function removeFkMateriel(Materiels $fkMateriel): static
    {
        if ($this->fk_materiels->removeElement($fkMateriel)) {
            // set the owning side to null (unless already changed)
            if ($fkMateriel->getFkProjets() === $this) {
                $fkMateriel->setFkProjets(null);
            }
        }

        return $this;
    }
}



    /*
    #[ORM\Column]
    #[Gedmo\TreeLeft]
    #[Gedmo\Versioned]
    private ?int $lft = null;

    #[ORM\Column]
    #[Gedmo\TreeRight]
    #[Gedmo\Versioned]
    private ?int $rgt = null;

    #[ORM\ManyToOne(inversedBy: 'children')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[Gedmo\TreeParent]
    #[Gedmo\Versioned]
    private ?Projet $parent = null;

    #[ORM\ManyToOne(targetEntity: Projet::class)]
    #[ORM\JoinColumn(name: 'tree_root', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Gedmo\TreeRoot]
    #[Gedmo\Versioned]
    private ?Projet $root = null;

    #[ORM\Column]
    #[Gedmo\TreeLevel]
    #[Gedmo\Versioned]
    private ?int $level = null;

    /**
     * @var Collection<int, Projet>
     /
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'parent')]
    #[ORM\OrderBy(['lft' => 'ASC'])]
    private Collection $children;
    */



    

    /*
    public function getLft(): ?int
    {
        return $this->lft;
    }

    public function setLft(int $lft): static
    {
        $this->lft = $lft;

        return $this;
    }

    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    public function setRgt(int $rgt): static
    {
        $this->rgt = $rgt;

        return $this;
    }

    public function getRoot(): ?self
    {
        return $this->root;
    }

    public function setRoot(?self $root): static
    {
        $this->root = $root;
        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent = null): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     /
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(Projet $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Projet $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }
    //*/