<?php

namespace App\Tests;

use App\Core\Entities\User\Name;
use App\Core\Entities\User\ResetToken;
use App\Tests\Other\TestUserFactory;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;


    public function testRegistrationByEmailOk(): void
    {
        $user = TestUserFactory::registerByEmail(
            $email = 'abc@example.com',
            $passwordHash = 'password_hash',
            $confirmToken = 'confirm_token',
            $firstName = 'New',
            $lastName = 'User',
        );

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals('New User', $user->getName()->getFullName());
        $this->assertEquals($passwordHash, $user->getPasswordHash());
        $this->assertEquals($confirmToken, $user->getConfirmToken());
        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
    }


    public function testRegistrationByEmailWithEmptyName(): void
    {
        $this->expectExceptionMessage('Last name must not be empty');
        $user = TestUserFactory::registerByEmail(
            $email = 'abc@example.com',
            $passwordHash = 'password_hash',
            $confirmToken = 'confirm_token',
            $firstName = 'New',
            $lastName = '',
        );
    }


    public function testRegistrationConfirmationOk(): void
    {
        $user = TestUserFactory::registerByEmail();
        $user->confirmRegistration();

        $this->assertFalse($user->isWait());
        $this->assertTrue($user->isActive());
        $this->assertNull($user->getConfirmToken());
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

        $this->assertEquals($user->getResetToken()->getToken(), 'test_reset_token');
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