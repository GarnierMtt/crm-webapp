<?php

namespace App\Entity;

use App\Repository\SitesContactsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SitesContactsRepository::class)]
class SitesContacts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $role = null;

    #[ORM\ManyToOne(inversedBy: 'fk_sites_contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sites $fk_sites = null;

    #[ORM\ManyToOne(inversedBy: 'fk_sites_contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contacts $fk_contacts = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getFkSites(): ?Sites
    {
        return $this->fk_sites;
    }

    public function setFkSites(?Sites $fk_sites): static
    {
        $this->fk_sites = $fk_sites;

        return $this;
    }

    public function getFkContacts(): ?Contacts
    {
        return $this->fk_contacts;
    }

    public function setFkContacts(?Contacts $fk_contacts): static
    {
        $this->fk_contacts = $fk_contacts;

        return $this;
    }
}
