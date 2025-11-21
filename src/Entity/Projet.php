<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
//#[Gedmo\Tree(type: 'nested')]
#[Gedmo\Loggable]
class Projet
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
    private ?\DateTime $dateDeb = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Gedmo\Versioned]
    private ?\DateTime $dateFin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $description = null;

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

    /**
     * @var Collection<int, RelationProjetSociete>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['societes' => ['societe', 'role', 'notes']]])]
    #[ORM\OneToMany(targetEntity: RelationProjetSociete::class, mappedBy: 'projet', orphanRemoval: true)]
    private Collection $societes;

    /**
     * @var Collection<int, LienFibre>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['lienFibres' => ['id']]])]
    #[ORM\OneToMany(targetEntity: LienFibre::class, mappedBy: 'projet')]
    private Collection $lienFibres;

    public function __construct()
    {
        //$this->children = new ArrayCollection();
        $this->societes = new ArrayCollection();
        $this->lienFibres = new ArrayCollection();
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

    public function getDateDeb(): ?\DateTime
    {
        return $this->dateDeb;
    }

    public function setDateDeb(?\DateTime $dateDeb): static
    {
        $this->dateDeb = $dateDeb;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

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

    /**
     * @return Collection<int, RelationProjetSociete>
     */
    public function getSocietes(): Collection
    {
        return $this->societes->map(
            fn($relation) => [
                'relation' => $relation,
                'societe' => $relation->getSociete(),
        ]);
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
