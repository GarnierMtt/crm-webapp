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

    public function __construct()
    {
        //$this->children = new ArrayCollection();
        $this->fk_liens_fibre = new ArrayCollection();
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