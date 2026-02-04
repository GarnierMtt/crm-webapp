<?php

namespace App\Entity;

use App\Repository\CommunesRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommunesRepository::class)]
#[Gedmo\Loggable]
class Communes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $code_postal = null;

    #[ORM\ManyToOne(inversedBy: 'fk_communes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pays $fk_pays = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    #[Context([AbstractNormalizer::ATTRIBUTES => ['fkPays' => ['id', 'libelle']]])]
    public function getFkPays(): ?Pays
    {
        return $this->fk_pays;
    }

    public function setFkPays(?Pays $fk_pays): static
    {
        $this->fk_pays = $fk_pays;

        return $this;
    }
}
