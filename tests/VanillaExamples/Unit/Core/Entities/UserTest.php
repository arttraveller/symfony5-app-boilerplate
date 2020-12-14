<?php

namespace App\Tests\VanillaExamples\Unit\Core\Entities;

use App\Domain\Entities\User\ResetToken;
use App\Tests\Other\TestUserFactory;
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

        self::assertTrue($user->getStatus()->isWait()());
        self::assertFalse($user->getStatus()->isActive());
    }


    public function testRegistrationConfirmationOk(): void
    {
        $user = TestUserFactory::registerByEmail();
        $user->confirmRegistration();

        self::assertFalse($user->getStatus()->isWait()());
        self::assertTrue($user->getStatus()->isActive());
        self::assertNull($user->getConfirmToken());
    }


    public function testThrowsExceptionWhenRegistrationConfirmationRepeat(): void
    {
        $user = TestUserFactory::registerByEmail();
        $user->confirmRegistration();

        $this->expectExceptionMessage('User already confirmed');
        $user->confirmRegistration();
    }


    public function testPasswordResetOk(): void
    {
        $user = TestUserFactory::registerByEmail();
        $user->confirmRegistration();
        $user->requestPasswordReset(new ResetToken('test_reset_token'));

        self::assertEquals($user->getResetToken()->getToken(), 'test_reset_token');
    }


    public function testThrowsExceptionWhenPasswordResetRepeatBeforeTokenExpires(): void
    {
        $user = TestUserFactory::registerByEmail();
        $user->confirmRegistration();
        $user->requestPasswordReset(new ResetToken('test_reset_token'));

        $this->expectExceptionMessage('Reset token was already requested');
        $user->requestPasswordReset(new ResetToken('test_reset_token'));
    }
}
