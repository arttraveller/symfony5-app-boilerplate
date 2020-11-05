<?php

namespace App\Tests;

use App\Core\Entities\User\User;
use App\Tests\Other\Factories\TestUserFactory;

class UsersRepositoryCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testRepoOk(FunctionalTester $I)
    {
        $user = TestUserFactory::registerByEmail(
            $email = 'abc@example.com',
            $passwordHash = 'password_hash',
            $confirmToken = 'confirm_token'
        );
        $I->haveInRepository($user);
        $I->seeInRepository(User::class, [
            'email' => $email,
            'passwordHash' => $passwordHash,
            'confirmToken' => $confirmToken,
        ]);
    }
}
