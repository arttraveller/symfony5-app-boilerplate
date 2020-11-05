<?php

namespace App\Core\Entities\User;

use App\Core\Entities\Entity;
use App\Exceptions\DomainException;
use App\Exceptions\UserNotActiveException;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repositories\UsersRepository")
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"email"}, name="users_unique_email"),
 *          @ORM\UniqueConstraint(columns={"confirm_token"}, name="users_unique_confirm_token"),
 *          @ORM\UniqueConstraint(columns={"reset_token_token"}, name="users_unique_reset_token"),
 *     },
 * )
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
     * @ORM\Embedded(class="ResetToken", columnPrefix="reset_token_")
     */
    private ?ResetToken $resetToken;

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
        $this->resetToken = null;
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


    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
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

    /**
     * Sets password reset token.
     *
     * @param ResetToken $newToken
     *
     * @throws UserNotActiveException
     * @throws DomainException
     */
    public function requestPasswordReset(ResetToken $newToken): void
    {
        if (!$this->isActive()) {
            throw new UserNotActiveException('User is not active');
        }
        if ($this->resetToken && !$this->resetToken->isExpired()) {
            throw new DomainException('Reset token was already requested');
        }
        $this->resetToken = $newToken;
    }


    /**
     * Resets password to a new one.
     *
     * @param string $passwordHash
     *
     * @throws DomainException
     */
    public function resetPassword(string $passwordHash): void
    {
        if (!$this->resetToken) {
            throw new DomainException('Password reset was not requested');
        }
        $this->passwordHash = $passwordHash;
        $this->resetToken = null;
    }

}
