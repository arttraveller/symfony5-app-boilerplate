<?php

namespace App\Tests\Unit\Core\Entities;

use App\Exceptions\DomainException;
use App\Tests\Other\Factories\TestUserFactory;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRegistrationByEmailOk(): void
    {
        $user = TestUserFactory::registerByEmail(
            $email = 'abc@example.com',
            $passwordHash = 'password_hash',
            $confirmToken = 'confirm_token'
        );
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($passwordHash, $user->getPasswordHash());
        self::assertEquals($confirmToken, $user->getConfirmToken());

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());
    }


    public function testRegistrationConfirmationOk(): void
    {
        $user = TestUserFactory::registerByEmail();
        $user->confirmRegistration();

        self::assertFalse($user->isWait());
        self::assertTrue($user->isActive());
        self::assertNull($user->getConfirmToken());
    }


    public function testThrowsExceptionWhenRegistrationConfirmationRepeat(): void
    {
        $user = TestUserFactory::registerByEmail();
        $user->confirmRegistration();

        $this->expectException(DomainException::class);
        $user->confirmRegistration();
    }

}
