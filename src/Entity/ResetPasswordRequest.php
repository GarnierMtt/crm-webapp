<?php

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;

#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $fk_utilisateurs = null;

    public function __construct(Utilisateurs $fk_utilisateurs, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->fk_utilisateurs = $fk_utilisateurs;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFkUtilisateurs(): Utilisateurs
    {
        return $this->fk_utilisateurs;
    }

    public function getUser(): Utilisateurs
    {
        return $this->fk_utilisateurs;
    }
}
