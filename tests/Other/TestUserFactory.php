<?php

namespace App\Tests\Other;

use App\Core\Entities\User\User;

class TestUserFactory
{
    public static function registerByEmail($email = 'test@example.com', $passwordHash = 'hash',  $confirmToken = 'token'): User
    {
        return User::registerByEmail($email, $passwordHash, $confirmToken);
    }

}
