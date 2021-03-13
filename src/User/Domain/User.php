<?php

namespace App\User\Domain;

use App\Shared\Exceptions\BusinessLogicViolationException;
use App\Post\Domain\Post;
use App\Shared\Exceptions\ResetTokenAlreadyRequestedException;
use App\Shared\Exceptions\UserNotActiveException;
use App\User\Domain\ValueObjects\Name;
use App\User\Domain\ValueObjects\ResetToken;
use App\User\Domain\ValueObjects\Role;
use App\User\Domain\ValueObjects\Status;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"email"}, name="users_unique_email"),
 *          @ORM\UniqueConstraint(columns={"confirm_token"}, name="users_unique_confirm_token"),
 *          @ORM\UniqueConstraint(columns={"reset_token_token"}, name="users_unique_reset_token"),
 *     },
 * )
 */
class User
{
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
     * @ORM\Embedded(class="App\User\Domain\ValueObjects\Name", columnPrefix=false)
     */
    private Name $name;

    /**
     * @ORM\Column(type="string", name="password_hash")
     */
    private string $passwordHash;

    /**
     * @ORM\Column(type="string", name="confirm_token", nullable=true)
     */
    private ?string $confirmToken;

    /**
     * @ORM\Embedded(class="App\User\Domain\ValueObjects\ResetToken", columnPrefix="reset_token_")
     */
    private ?ResetToken $resetToken;

    /**
     * @ORM\Column(type="user_role", name="role")
     */
    private Role $role;

    /**
     * @ORM\Column(type="user_status", name="status")
     */
    private Status $status;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default"="CURRENT_TIMESTAMP"}, nullable=false)
     */
    private DateTimeImmutable $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     */
    private Collection $posts;


    private function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->resetToken = null;
        $this->role = Role::user();
        $this->posts = new ArrayCollection();
    }

    /**
     * Registers user by email.
     *
     * @param string $email
     * @param string $passwordHash
     * @param string $confirmToken
     * @return User
     */
    public static function registerByEmail(
        string $email,
        string $passwordHash,
        string $confirmToken,
        Name $name
    ): self
    {
        $newUser = new self();
        $newUser->email = $email;
        $newUser->name = $name;
        $newUser->passwordHash = $passwordHash;
        $newUser->confirmToken = $confirmToken;
        $newUser->status = Status::wait();

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

    public function getName(): Name
    {
        return $this->name;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function changeRole(Role $newRole): void
    {
        if ($this->role->getName() === $newRole->getName()) {
            throw new BusinessLogicViolationException('Same role already used');
        }
        $this->role = $newRole;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * Confirms user registration.
     * @throws BusinessLogicViolationException
     */
    public function confirmRegistration(): void
    {
        if (!$this->status->isWait()) {
            throw new BusinessLogicViolationException('User already confirmed');
        }
        $this->status = Status::active();
        $this->confirmToken = null;
    }

    /**
     * Sets password reset token.
     *
     * @param ResetToken $newToken
     * @throws UserNotActiveException
     * @throws ResetTokenAlreadyRequestedException
     */
    public function requestPasswordReset(ResetToken $newToken): void
    {
        if (!$this->status->isActive()) {
            throw new UserNotActiveException('User is not active');
        }
        if ($this->resetToken && !$this->resetToken->isExpired()) {
            throw new ResetTokenAlreadyRequestedException('Reset token was already requested');
        }
        $this->resetToken = $newToken;
    }

    /**
     * Resets password to a new one.
     *
     * @param string $passwordHash
     * @throws BusinessLogicViolationException
     */
    public function resetPassword(string $passwordHash): void
    {
        if (!$this->resetToken) {
            throw new BusinessLogicViolationException('Password reset was not requested');
        }
        $this->passwordHash = $passwordHash;
        $this->resetToken = null;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }
}
