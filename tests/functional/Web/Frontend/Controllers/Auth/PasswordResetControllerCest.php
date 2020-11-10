<?php

namespace App\Tests;

use App\Core\Entities\User\ResetToken;
use App\Core\Entities\User\User;
use App\Core\Repositories\UsersRepository;
use App\DataFixtures\UsersFixtures;

class PasswordResetControllerCest
{
    public function _before(FunctionalTester $I)
    {
    }

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
        $usersRepository = $I->grabService(UsersRepository::class);
        $user = $usersRepository->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $resetToken = new ResetToken('test');
        $user->requestPasswordReset($resetToken);
        $usersRepository->flush();

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

        $usersRepository = $I->grabService(UsersRepository::class);
        $user = $usersRepository->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->assertNotEmpty($user->getResetToken()->getToken());
    }


    public function testResetOk(FunctionalTester $I)
    {
        $token = 'reset_token';
        $usersRepository = $I->grabService(UsersRepository::class);
        /** @var User $user */
        $user = $usersRepository->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $oldPasswordHash = $user->getPasswordHash();
        $resetToken = new ResetToken($token);
        $user->requestPasswordReset($resetToken);
        $usersRepository->flush();

        $I->amOnPage('/password/reset/' . $token);
        $I->seeResponseCodeIs(200);
        $I->fillField('Password', 'NewPassword');
        $I->click('Save password');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/');
        $I->see('Password was successfully changed.', 'div.alert-success');

        $user = $usersRepository->getOneByEmail(UsersFixtures::CONFIRMED_USER_EMAIL);
        $I->assertNotEquals($user->getPasswordHash(), $oldPasswordHash);
    }

}
