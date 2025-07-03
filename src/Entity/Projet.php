<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[Gedmo\Loggable]
#[Gedmo\Tree(type: 'nested')]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $dateEnd = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    #[Gedmo\TreeLeft]
    private ?int $lft = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    #[Gedmo\TreeRight]
    private ?int $rgt = null;

    #[ORM\ManyToOne(inversedBy: 'children')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[Gedmo\Versioned]
    #[Gedmo\TreeParent]
    private ?Projet $parent = null;

    #[ORM\ManyToOne(targetEntity: Projet::class)]
    #[ORM\JoinColumn(name: 'tree_root', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Gedmo\Versioned]
    #[Gedmo\TreeRoot]
    private ?Projet $root = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    #[Gedmo\TreeLevel]
    private ?int $level = null;

    /**
     * @var Collection<int, Projet>
     */
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'parent')]
    #[ORM\OrderBy(['lft' => 'ASC'])]
    private Collection $children;

    /**
     * @var Collection<int, RelationProjetSociete>
     */
    #[ORM\OneToMany(targetEntity: RelationProjetSociete::class, mappedBy: 'projet', orphanRemoval: true)]
    private Collection $societes;

    /**
     * @var Collection<int, LienFibre>
     */
    #[ORM\OneToMany(targetEntity: LienFibre::class, mappedBy: 'projet')]
    private Collection $lienFibres;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->societes = new ArrayCollection();
        $this->lienFibres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getDateEnd(): ?\DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTime $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

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
     */
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

    /**
     * @return Collection<int, RelationProjetSociete>
     */
    public function getSocietes(): Collection
    {
        return $this->societes;
    }

    public function addSociete(RelationProjetSociete $societe): static
    {
        if (!$this->societes->contains($societe)) {
            $this->societes->add($societe);
            $societe->setProjet($this);
        }

        return $this;
    }

    public function removeSociete(RelationProjetSociete $societe): static
    {
        if ($this->societes->removeElement($societe)) {
            // set the owning side to null (unless already changed)
            if ($societe->getProjet() === $this) {
                $societe->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LienFibre>
     */
    public function getLienFibres(): Collection
    {
        return $this->lienFibres;
    }

    public function addLienFibre(LienFibre $lienFibre): static
    {
        if (!$this->lienFibres->contains($lienFibre)) {
            $this->lienFibres->add($lienFibre);
            $lienFibre->setProjet($this);
        }

        return $this;
    }

    public function removeLienFibre(LienFibre $lienFibre): static
    {
        if ($this->lienFibres->removeElement($lienFibre)) {
            // set the owning side to null (unless already changed)
            if ($lienFibre->getProjet() === $this) {
                $lienFibre->setProjet(null);
            }
        }

        return $this;
    }
}
