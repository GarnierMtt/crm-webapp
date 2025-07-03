<?php

namespace App\Entity;

use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: SocieteRepository::class)]
#[Gedmo\Loggable]
class Societe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $telephoneStandard = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $siret = null;

    /**
     * @var Collection<int, Contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'societe')]
    private Collection $contacts;

    /**
     * @var Collection<int, RelationSocieteAdresse>
     */
    #[ORM\OneToMany(targetEntity: RelationSocieteAdresse::class, mappedBy: 'societe', orphanRemoval: true)]
    private Collection $Adresses;

    /**
     * @var Collection<int, RelationProjetSociete>
     */
    #[ORM\OneToMany(targetEntity: RelationProjetSociete::class, mappedBy: 'societe')]
    private Collection $Projets;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->Adresses = new ArrayCollection();
        $this->Projets = new ArrayCollection();
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

    public function getTelephoneStandard(): ?string
    {
        return $this->telephoneStandard;
    }

    public function setTelephoneStandard(?string $telephoneStandard): static
    {
        $this->telephoneStandard = $telephoneStandard;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setSociete($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getSociete() === $this) {
                $contact->setSociete(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RelationSocieteAdresse>
     */
    public function getAdresses(): Collection
    {
        return $this->Adresses;
    }

    public function addAdress(RelationSocieteAdresse $adress): static
    {
        if (!$this->Adresses->contains($adress)) {
            $this->Adresses->add($adress);
            $adress->setSociete($this);
        }

        return $this;
    }

    public function removeAdress(RelationSocieteAdresse $adress): static
    {
        if ($this->Adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getSociete() === $this) {
                $adress->setSociete(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RelationProjetSociete>
     */
    public function getProjets(): Collection
    {
        return $this->Projets;
    }

    public function addProjet(RelationProjetSociete $projet): static
    {
        if (!$this->Projets->contains($projet)) {
            $this->Projets->add($projet);
            $projet->setSociete($this);
        }

        return $this;
    }

    public function removeProjet(RelationProjetSociete $projet): static
    {
        if ($this->Projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getSociete() === $this) {
                $projet->setSociete(null);
            }
        }

        return $this;
    }



    public function __toString(): string
    {
        return $this->name;
    }
}
