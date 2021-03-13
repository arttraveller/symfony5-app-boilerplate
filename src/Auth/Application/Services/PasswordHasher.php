<?php

namespace App\Auth\Application\Services;

use RuntimeException;

class PasswordHasher
{
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2I);
        if ($hash === false) {
            throw new RuntimeException('Unable to generate hash.');
        }

        return $hash;
    }

    public function isPasswordValid(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
