<?php

namespace App\Tests;

use App\Core\Entities\User\ResetToken;
use App\Core\Entities\User\Role;
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

        // Credentials, name
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals('New User', $user->getName()->getFullName());
        $this->assertEquals($passwordHash, $user->getPasswordHash());
        $this->assertEquals($confirmToken, $user->getConfirmToken());
        // Status
        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());
        // Role
        $this->assertTrue($user->getRole()->isUser());
        $this->assertFalse($user->getRole()->isAdmin());
        // Created at
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


    public function testThrowsExceptionWhenChangeRoleToSame(): void
    {
        $user = TestUserFactory::registerByEmail();
        $this->expectExceptionMessage('Same role already used');
        $user->changeRole(Role::user());
    }


    public function testChangeRoleOk(): void
    {
        $user = TestUserFactory::registerByEmail();
        $user->changeRole(Role::admin());
        $this->assertFalse($user->getRole()->isUser());
        $this->assertTrue($user->getRole()->isAdmin());
    }

}