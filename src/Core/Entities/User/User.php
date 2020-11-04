<?php

namespace App\Core\Entities\User;

use App\Core\Entities\Entity;
use App\Exceptions\DomainException;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User extends Entity
{
    private const STATUS_WAIT = 1;
    private const STATUS_ACTIVE = 2;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $email;

    /**
     * @ORM\Column(type="string", name="password_hash")
     */
    private string $passwordHash;

    /**
     * @ORM\Column(type="string", name="confirm_token", nullable=true)
     */
    private ?string $confirmToken;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default"="CURRENT_TIMESTAMP"}, nullable=false)
     */
    private DateTimeImmutable $createdAt;



    private function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }


    /**
     * Registers user by email.
     *
     * @param string $email
     * @param string $passwordHash
     * @param string $confirmToken
     * @return User
     */
    public static function registerByEmail(string $email, string $passwordHash, string $confirmToken): self
    {
        $newUser = new self();
        $newUser->email = $email;
        $newUser->passwordHash = $passwordHash;
        $newUser->confirmToken = $confirmToken;
        $newUser->status = self::STATUS_WAIT;

        return $newUser;
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getEmail(): string
    {
        return $this->email;
    }


    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }


    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }


    /**
     * Returns whether the user has STATUS_WAIT.
     *
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * Returns whether the user has STATUS_ACTIVE.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }


    /**
     * Confirms user registration.
     */
    public function confirmRegistration(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('User already confirmed');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

}
