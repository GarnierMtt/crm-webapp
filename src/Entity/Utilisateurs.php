<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
#[Gedmo\Loggable]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['mel'])]
#[UniqueEntity(fields: ['mel'], message: 'Il y a déja un utilisateur avec ce mél.')]
class Utilisateurs implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Gedmo\Versioned]
    private ?string $mel = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Gedmo\Versioned]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Gedmo\Versioned]
    private ?string $mot_de_passe = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Versioned]
    private ?string $nom = null;

    #[ORM\Column]
    #[Gedmo\Versioned]
    private bool $actif = false;

    /**
     * @var Collection<int, Taches>
     */
    #[ORM\ManyToMany(targetEntity: Taches::class, mappedBy: 'fk_utilisateurs')]
    private Collection $fk_taches;

    public function __construct()
    {
        $this->fk_taches = new ArrayCollection();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mel;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setPassword(string $mot_de_passe): static
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    /**
     * @see UserInterface
     */
    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMel(): ?string
    {
        return $this->mel;
    }

    public function setMel(string $mel): static
    {
        $this->mel = $mel;

        return $this;
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

    public function actif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, Taches>
     */
    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkTaches' => ['id', 'libelle']]])]
    public function getFkTaches(): Collection
    {
        return $this->fk_taches;
    }

    public function addFkTach(Taches $fkTach): static
    {
        if (!$this->fk_taches->contains($fkTach)) {
            $this->fk_taches->add($fkTach);
            $fkTach->addFkUtilisateur($this);
        }

        return $this;
    }

    public function removeFkTach(Taches $fkTach): static
    {
        if ($this->fk_taches->removeElement($fkTach)) {
            $fkTach->removeFkUtilisateur($this);
        }

        return $this;
    }
}
