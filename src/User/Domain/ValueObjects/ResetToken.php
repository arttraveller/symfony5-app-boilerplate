<?php

namespace App\User\Domain\ValueObjects;

use App\Shared\Exceptions\BusinessLogicViolationException;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class ResetToken
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $token;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $expires;


    public function __construct(string $token)
    {
        if (empty($token)) {
            throw new BusinessLogicViolationException('Token is empty');
        }
        $now = new DateTimeImmutable();
        $this->token = $token;
        $this->expires = $now->add(new DateInterval('PT2H'));
    }


    public function isExpired(): bool
    {
        $now = new DateTimeImmutable();

        return $this->expires <= $now;
    }


    public function getToken(): ?string
    {
        return $this->token;
    }


    public function isEmpty(): bool
    {
        return empty($this->token);
    }
}
