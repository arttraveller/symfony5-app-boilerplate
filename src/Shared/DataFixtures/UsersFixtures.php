<?php

namespace App\Shared\DataFixtures;

use App\Auth\Application\Services\PasswordHasher;
use App\Auth\Application\Services\Tokenizer;
use App\User\Domain\User;
use App\User\Domain\ValueObjects\Name;
use App\User\Domain\ValueObjects\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public const ADMIN_EMAIL = 'admin@example.com';
    public const ADMIN_PASSWORD = 'admin_password';
    public const CONFIRMED_USER_EMAIL = 'user@example.com';
    public const CONFIRMED_USER_PASSWORD = 'password';
    public const CONFIRMED_USER_FIRSTNAME = 'John';
    public const CONFIRMED_USER_LASTNAME = 'Smith';
    public const UNCONFIRMED_USER_EMAIL = 'unconfirmed@example.com';
    public const UNCONFIRMED_USER_TOKEN = 'confirmTokenHere';

    private PasswordHasher $passwordHasher;
    private Tokenizer $tokenizer;


    public static function getConfirmedUserFullName()
    {
        return self::CONFIRMED_USER_FIRSTNAME . ' ' . self::CONFIRMED_USER_LASTNAME;
    }


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
        $admin = $this->createAdmin();
        $manager->persist($admin);

        $manager->flush();
    }

    public function createConfirmedUser(): User
    {
        $user = User::registerByEmail(
            self::CONFIRMED_USER_EMAIL,
            $this->passwordHasher->hash(self::CONFIRMED_USER_PASSWORD),
            $this->tokenizer->generateConfirmToken(),
            new Name(self::CONFIRMED_USER_FIRSTNAME, self::CONFIRMED_USER_LASTNAME)
        );
        $user->confirmRegistration();

        return $user;
    }


    public function createUnconfirmedUser(): User
    {
        $user = User::registerByEmail(
            self::UNCONFIRMED_USER_EMAIL,
            $this->passwordHasher->hash('password'),
            self::UNCONFIRMED_USER_TOKEN,
            new Name('Jane', 'Smith')
        );

        return $user;
    }



    public function createAdmin(): User
    {
        $admin = User::registerByEmail(
            self::ADMIN_EMAIL,
            $this->passwordHasher->hash(self::ADMIN_PASSWORD),
            $this->tokenizer->generateConfirmToken(),
            new Name('Mike', 'Williams')
        );
        $admin->confirmRegistration();
        $admin->changeRole(Role::admin());

        return $admin;
    }

}
