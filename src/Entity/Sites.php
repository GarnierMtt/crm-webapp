<?php

namespace App\Entity;

use App\Repository\SitesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SitesRepository::class)]
class Sites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $numero_voie = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_voie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement = null;

    #[ORM\ManyToOne(inversedBy: 'fk_sites')]
    private ?Communes $fk_communes = null;

    /**
     * @var Collection<int, SitesContacts>
     */
    #[ORM\OneToMany(targetEntity: SitesContacts::class, mappedBy: 'fk_sites', orphanRemoval: true)]
    private Collection $fk_sites_contacts;

    /**
     * @var Collection<int, LiensFibre>
     */
    #[ORM\OneToMany(targetEntity: LiensFibre::class, mappedBy: 'point_a')]
    private Collection $fk_liensFibre;

    #[ORM\ManyToOne(inversedBy: 'fk_sites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Societes $fk_societes = null;

    /**
     * @var Collection<int, Materiels>
     */
    #[ORM\OneToMany(targetEntity: Materiels::class, mappedBy: 'fk_sites')]
    private Collection $fk_materiels;

    public function __construct()
    {
        $this->fk_sites_contacts = new ArrayCollection();
        $this->fk_liensFibre = new ArrayCollection();
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

    public function getNumeroVoie(): ?string
    {
        return $this->numero_voie;
    }

    public function setNumeroVoie(string $numero_voie): static
    {
        $this->numero_voie = $numero_voie;

        return $this;
    }

    public function getNomVoie(): ?string
    {
        return $this->nom_voie;
    }

    public function setNomVoie(string $nom_voie): static
    {
        $this->nom_voie = $nom_voie;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): static
    {
        $this->complement = $complement;

        return $this;
    }

    public function getFkCommunes(): ?Communes
    {
        return $this->fk_communes;
    }

    public function setFkCommunes(?Communes $fk_communes): static
    {
        $this->fk_communes = $fk_communes;

        return $this;
    }

    /**
     * @return Collection<int, SitesContacts>
     */
    public function getFkSitesContacts(): Collection
    {
        return $this->fk_sites_contacts;
    }

    public function addFkSitesContact(SitesContacts $fkSitesContact): static
    {
        if (!$this->fk_sites_contacts->contains($fkSitesContact)) {
            $this->fk_sites_contacts->add($fkSitesContact);
            $fkSitesContact->setFkSites($this);
        }

        return $this;
    }

    public function removeFkSitesContact(SitesContacts $fkSitesContact): static
    {
        if ($this->fk_sites_contacts->removeElement($fkSitesContact)) {
            // set the owning side to null (unless already changed)
            if ($fkSitesContact->getFkSites() === $this) {
                $fkSitesContact->setFkSites(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LiensFibre>
     */
    public function getFkLiensFibre(): Collection
    {
        return $this->fk_liensFibre;
    }

    public function addFkLiensFibre(LiensFibre $fkLiensFibre): static
    {
        if (!$this->fk_liensFibre->contains($fkLiensFibre)) {
            $this->fk_liensFibre->add($fkLiensFibre);
            $fkLiensFibre->setPointA($this);
        }

        return $this;
    }

    public function removeFkLiensFibre(LiensFibre $fkLiensFibre): static
    {
        if ($this->fk_liensFibre->removeElement($fkLiensFibre)) {
            // set the owning side to null (unless already changed)
            if ($fkLiensFibre->getPointA() === $this) {
                $fkLiensFibre->setPointA(null);
            }
        }

        return $this;
    }

    public function getFkSocietes(): ?Societes
    {
        return $this->fk_societes;
    }

    public function setFkSocietes(?Societes $fk_societes): static
    {
        $this->fk_societes = $fk_societes;

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
            $fkMateriel->setFkSites($this);
        }

        return $this;
    }

    public function removeFkMateriel(Materiels $fkMateriel): static
    {
        if ($this->fk_materiels->removeElement($fkMateriel)) {
            // set the owning side to null (unless already changed)
            if ($fkMateriel->getFkSites() === $this) {
                $fkMateriel->setFkSites(null);
            }
        }

        return $this;
    }
}
