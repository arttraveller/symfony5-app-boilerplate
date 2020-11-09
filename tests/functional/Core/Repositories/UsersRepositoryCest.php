<?php

namespace App\Tests;

use App\Core\Entities\User\User;
use App\Core\Repositories\UsersRepository;
use App\DataFixtures\UsersFixtures;

class UsersRepositoryCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function testRepoOk(FunctionalTester $I)
    {
        $usersRepo = $I->grabService(UsersRepository::class);
        $user = $usersRepo->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->assertInstanceOf(User::class, $user);
    }
}
