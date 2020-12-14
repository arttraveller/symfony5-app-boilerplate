<?php

namespace App\Domain\Services\Auth;

use App\Exceptions\DomainException;

class PasswordHasher
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);
        if ($hash === false) {
            throw new DomainException('Unable to generate hash.');
        }
        return $hash;
    }


    public function isPasswordValid(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
