<?php

namespace App\Entity;

use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Gedmo\Versioned]
    private ?string $telephoneStandard = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $siret = null;

    /**
     * @var Collection<int, Contact>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['contacts' => ['id', 'nom', 'prenom', 'mel', 'telephone', 'post']]])]
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'societe')]
    private Collection $contacts;

    /**
     * @var Collection<int, RelationProjetSociete>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['projets' => ['id', 'role', 'notes', 'projet']]])]
    #[ORM\OneToMany(targetEntity: RelationProjetSociete::class, mappedBy: 'societe')]
    private Collection $projets;

    /**
     * @var Collection<int, Adresse>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['adresses' => ['id', 'nomSite', 'numeroVoie', 'nomVoie', 'codePostal', 'commune', 'pays', 'complement', 'contacts']]])]
    #[ORM\OneToMany(targetEntity: Adresse::class, mappedBy: 'societe')]
    private Collection $adresses;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->projets = new ArrayCollection();
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
     * @return Collection<int, RelationProjetSociete>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(RelationProjetSociete $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->setSociete($this);
        }

        return $this;
    }

    public function removeProjet(RelationProjetSociete $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getSociete() === $this) {
                $projet->setSociete(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setSociete($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getSociete() === $this) {
                $adress->setSociete(null);
            }
        }

        return $this;
    }



    public function __toString(): string
    {
        return $this->nom;
    }
}
