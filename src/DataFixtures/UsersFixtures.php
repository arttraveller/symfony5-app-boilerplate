<?php

namespace App\DataFixtures;

use App\Core\Entities\User\User;
use App\Core\Services\Auth\PasswordHasher;
use App\Core\Services\Auth\Tokenizer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public const CONFIRMED_USER_EMAIL = 'user@example.com';
    public const CONFIRMED_USER_PASSwORD = 'password';
    public const UNCONFIRMED_USER_TOKEN = 'confirmTokenHere';

    private PasswordHasher $passwordHasher;
    private Tokenizer $tokenizer;


    public function __construct(PasswordHasher $hasher, Tokenizer $tokenizer)
    {
        $this->passwordHasher = $hasher;
        $this->tokenizer = $tokenizer;
    }

    public function load(ObjectManager $manager)
    {
        $confirmedUser = $this->createConfirmedUser();
        $manager->persist($confirmedUser);
        $unconfirmedUser = $this->createUnconfirmedUser();
        $manager->persist($unconfirmedUser);

        $manager->flush();
    }

    public function createConfirmedUser(): User
    {
        $user = User::registerByEmail(
            self::CONFIRMED_USER_EMAIL,
            $this->passwordHasher->hash(self::CONFIRMED_USER_PASSwORD),
            $this->tokenizer->generateConfirmToken()
        );
        $user->confirmRegistration();

        return $user;
    }


    public function createUnconfirmedUser(): User
    {
        $user = User::registerByEmail(
            'unconfirmed@example.com',
            $this->passwordHasher->hash('password'),
            self::UNCONFIRMED_USER_TOKEN
        );

        return $user;
    }

}
