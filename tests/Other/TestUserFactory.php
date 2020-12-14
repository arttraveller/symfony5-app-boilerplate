<?php

namespace App\Tests\Other;

use App\Domain\Entities\User\Name;
use App\Domain\Entities\User\User;

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
