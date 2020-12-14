<?php

namespace App\Domain\Entities\User;

use App\Exceptions\DomainException;

/**
 */
class Status
{
    private const WAIT = 1;
    private const ACTIVE = 2;

    private int $status;


    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }


    public function __construct(int $statusId)
    {
        if (!in_array($statusId, [self::WAIT, self::ACTIVE])) {
            throw new DomainException('Incorrect user status');
        }
        $this->status = $statusId;
    }


    public function __toString(): string
    {
        switch ($this->status) {
            case self::WAIT:
                return 'Wait';
            case self::ACTIVE:
                return 'Active';
            default:
                throw new DomainException('Unknown status');
        }
    }


    /**
     * Returns whether the user has STATUS_WAIT.
     *
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::WAIT;
    }


    /**
     * Returns whether the user has STATUS_ACTIVE.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::ACTIVE;
    }


    public function getId(): int
    {
        return $this->status;
    }
}
