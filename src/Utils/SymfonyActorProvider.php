<?php

namespace App\Utils;

use Gedmo\Tool\ActorProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class SymfonyActorProvider implements ActorProviderInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return object|string|null
     */
    public function getActor()
    {
        $token = $this->tokenStorage->getToken();

        return $token ? $token->getUser() : null;
    }
}