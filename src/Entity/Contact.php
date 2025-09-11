<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[Gedmo\Loggable]
class Contact
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

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $post = null;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[Gedmo\Versioned]
    private ?Societe $societe = null;

    /**
     * @var Collection<int, RelationContactAdresse>
     */
    #[ORM\OneToMany(targetEntity: RelationContactAdresse::class, mappedBy: 'contact')]
    private Collection $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
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

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(?string $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * @return Collection<int, RelationContactAdresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdresse(RelationContactAdresse $relationContactAdresse): static
    {
        if (!$this->adresses->contains($relationContactAdresse)) {
            $this->adresses->add($relationContactAdresse);
            $relationContactAdresse->setContact($this);
        }

        return $this;
    }

    public function removetAdresse(RelationContactAdresse $relationContactAdresse): static
    {
        if ($this->adresses->removeElement($relationContactAdresse)) {
            // set the owning side to null (unless already changed)
            if ($relationContactAdresse->getContact() === $this) {
                $relationContactAdresse->setContact(null);
            }
        }

        return $this;
    }



    public function __toString(): string
    {
        return $this->nom . " " . $this->prenom;
    }
}
