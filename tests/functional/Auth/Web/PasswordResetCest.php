<?php

namespace App\Tests;

use App\Shared\DataFixtures\UsersFixtures;
use App\Tests\Other\UsersServices;
use App\User\Domain\ValueObjects\ResetToken;

class PasswordResetControllerCest
{
    use UsersServices;

    public function testRequestUnconfirmedUser(FunctionalTester $I)
    {
        $I->amOnPage('/password/reset');
        $I->submitForm('form[name=request_password_reset_form]', [
            'request_password_reset_form[email]' => UsersFixtures::UNCONFIRMED_USER_EMAIL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/');
        $I->see('Please confirm your email first.', 'div.alert-danger');
    }

    public function testRequestRepeat(FunctionalTester $I)
    {
        $usersQs = $this->getUsersQueryService($I);
        $usersRepo = $this->getUsersRepository($I);;
        $user = $usersQs->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $resetToken = new ResetToken('test');
        $user->requestPasswordReset($resetToken);
        $usersRepo->update($user);

        $I->amOnPage('/password/reset');
        $I->submitForm('form[name=request_password_reset_form]', [
            'request_password_reset_form[email]' => $user->getEmail(),
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/');
        $I->see('Reset token was already requested.', 'div.alert-danger');
    }

    public function testRequestOk(FunctionalTester $I)
    {
        $I->amOnPage('/signin');
        $I->seeLink('Forgot password?');
        $I->click('Forgot password?');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/password/reset');
        $I->fillField('Email', UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->click('Reset');

        $I->seeResponseCodeIs(200);
        $I->see('The email with further instructions was sent to the submitted email address.', 'div.alert-success');

        $usersQs = $this->getUsersQueryService($I);
        $user = $usersQs->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->assertNotEmpty($user->getResetToken()->getToken());
    }

    public function testResetOk(FunctionalTester $I)
    {
        $token = 'reset_token';
        $usersQs = $this->getUsersQueryService($I);
        $usersRepo = $this->getUsersRepository($I);;
        $user = $usersQs->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $oldPasswordHash = $user->getPasswordHash();
        $resetToken = new ResetToken($token);
        $user->requestPasswordReset($resetToken);
        $usersRepo->update($user);

        $I->amOnPage('/password/reset/' . $token);
        $I->seeResponseCodeIs(200);
        $I->fillField('Password', 'NewPassword');
        $I->click('Save password');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/');
        $I->see('Password was successfully changed.', 'div.alert-success');

        $user = $usersQs->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->assertNotEquals($user->getPasswordHash(), $oldPasswordHash);
    }
}
