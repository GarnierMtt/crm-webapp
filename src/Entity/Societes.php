<?php

namespace App\Entity;

use App\Repository\SocietesRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SocietesRepository::class)]
#[Gedmo\Loggable]
class Societes
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
    private ?string $telephone_standard = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $siret = null;

    /**
     * @var Collection<int, Contacts>
     */
    #[ORM\OneToMany(targetEntity: Contacts::class, mappedBy: 'fk_societes')]
    private Collection $fk_contacts;

    /**
     * @var Collection<int, Projets>
     */
    #[ORM\OneToMany(targetEntity: Projets::class, mappedBy: 'societe_client')]
    private Collection $fk_projets;

    /**
     * @var Collection<int, Taches>
     */
    #[ORM\OneToMany(targetEntity: Taches::class, mappedBy: 'fk_societes')]
    private Collection $fk_taches;

    /**
     * @var Collection<int, Sites>
     */
    #[ORM\OneToMany(targetEntity: Sites::class, mappedBy: 'fk_societes')]
    private Collection $fk_sites;

    public function __construct()
    {
        $this->fk_contacts = new ArrayCollection();
        $this->fk_projets = new ArrayCollection();
        $this->fk_taches = new ArrayCollection();
        $this->fk_sites = new ArrayCollection();
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
        return $this->telephone_standard;
    }

    public function setTelephoneStandard(?string $telephone_standard): static
    {
        $this->telephone_standard = $telephone_standard;

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
     * @return Collection<int, Contacts>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkContacts' => ['id', 'nom', 'prenom', 'mel', 'telephone']]])]
    public function getFkContacts(): Collection
    {
        return $this->fk_contacts;
    }

    public function addFkContacts(Contacts $fk_contacts): static
    {
        if (!$this->fk_contacts->contains($fk_contacts)) {
            $this->fk_contacts->add($fk_contacts);
            $fk_contacts->setFkSocietes($this);
        }

        return $this;
    }

    public function removeFkContacts(Contacts $fk_contacts): static
    {
        if ($this->fk_contacts->removeElement($fk_contacts)) {
            // set the owning side to null (unless already changed)
            if ($fk_contacts->getFkSocietes() === $this) {
                $fk_contacts->setFkSocietes(null);
            }
        }

        return $this;
    }



    public function __toString(): string
    {
        return $this->nom;
    }

    /**
     * @return Collection<int, Projets>
     */
    public function getFkProjets(): Collection
    {
        return $this->fk_projets;
    }

    public function addFkProjet(Projets $fkProjet): static
    {
        if (!$this->fk_projets->contains($fkProjet)) {
            $this->fk_projets->add($fkProjet);
            $fkProjet->setSocieteClient($this);
        }

        return $this;
    }

    public function removeFkProjet(Projets $fkProjet): static
    {
        if ($this->fk_projets->removeElement($fkProjet)) {
            // set the owning side to null (unless already changed)
            if ($fkProjet->getSocieteClient() === $this) {
                $fkProjet->setSocieteClient(null);
            }
        }

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
            $fkTach->setFkSocietes($this);
        }

        return $this;
    }

    public function removeFkTach(Taches $fkTach): static
    {
        if ($this->fk_taches->removeElement($fkTach)) {
            // set the owning side to null (unless already changed)
            if ($fkTach->getFkSocietes() === $this) {
                $fkTach->setFkSocietes(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sites>
     */
    public function getFkSites(): Collection
    {
        return $this->fk_sites;
    }

    public function addFkSite(Sites $fkSite): static
    {
        if (!$this->fk_sites->contains($fkSite)) {
            $this->fk_sites->add($fkSite);
            $fkSite->setFkSocietes($this);
        }

        return $this;
    }

    public function removeFkSite(Sites $fkSite): static
    {
        if ($this->fk_sites->removeElement($fkSite)) {
            // set the owning side to null (unless already changed)
            if ($fkSite->getFkSocietes() === $this) {
                $fkSite->setFkSocietes(null);
            }
        }

        return $this;
    }
}
