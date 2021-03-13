<?php

namespace App\User\Domain\ValueObjects;

use App\Shared\Exceptions\BusinessLogicViolationException;

/**
 */
class Role
{
    private const USER = 'ROLE_USER';
    private const ADMIN = 'ROLE_ADMIN';

    private string $name;


    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }


    public function __construct(string $roleName)
    {
        if (!in_array($roleName, [self::USER, self::ADMIN])) {
            throw new BusinessLogicViolationException('Incorrect user role');
        }
        $this->name = $roleName;
    }



    public function isUser(): bool
    {
        return $this->name === self::USER;
    }


    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }


    public function getName(): string
    {
        return $this->name;
    }
}
