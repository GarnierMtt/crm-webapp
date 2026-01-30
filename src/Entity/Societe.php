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

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
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



    public function __toString(): string
    {
        return $this->nom;
    }
}
