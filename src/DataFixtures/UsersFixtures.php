<?php

namespace App\DataFixtures;

use App\Core\Entities\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public const CONFIRMED_USER_EMAIL = 'user@example.com';


    public function load(ObjectManager $manager)
    {
        $confirmedUser = $this->createConfirmedUser();

        $manager->persist($confirmedUser);
        $manager->flush();
    }

    public function createConfirmedUser(): User
    {
        $user = User::registerByEmail(
            self::CONFIRMED_USER_EMAIL,
            'todo_hash',
            'todo_token',
        );
        $user->confirmRegistration();

        return $user;
    }

}
