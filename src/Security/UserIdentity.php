<?php

namespace App\Security;

use App\Core\Entities\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentity implements UserInterface
{
    private User $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->user->getId();
    }


    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->user->getEmail();
    }


    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->user->getPasswordHash();
    }

    
    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return [];
    }

    
    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    
    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
    }


    public function isActive(): string
    {
        return $this->user->isActive();
    }
}