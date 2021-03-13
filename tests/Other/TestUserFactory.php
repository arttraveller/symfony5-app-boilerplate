<?php

namespace App\Tests\Other;

use App\User\Domain\User;
use App\User\Domain\ValueObjects\Name;

class TestUserFactory
{
    public static function registerByEmail(
        $email = 'test@example.com',
        $passwordHash = 'hash',
        $confirmToken = 'token',
        $firstName = 'John',
        $lastName = 'Smith'
    ): User
    {
        $name = new Name($firstName, $lastName);
        return User::registerByEmail($email, $passwordHash, $confirmToken, $name);
    }

}
