<?php

namespace App\Tests;

use App\Core\Repositories\UsersRepository;
use App\DataFixtures\UsersFixtures;
use App\Tests\FunctionalTester;

class SignUpControllerCest
{
    public function _before(FunctionalTester $I)
    {
    }


    public function testSignUpWithInvalidData(FunctionalTester $I)
    {
        $I->amOnPage('/signup');
        $I->submitForm('form[name=sign_up_form]', [
            'sign_up_form[email]' => 'not-valid-email',
            'sign_up_form[password]' => '123',
        ]);

        $I->seeCurrentUrlEquals('/signup');
        $I->seeNumberOfElements('input.is-invalid', 2);
    }


    public function testSignUpWithExistsEmail(FunctionalTester $I)
    {
        $I->amOnPage('/signup');
        $I->submitForm('form[name=sign_up_form]', [
            'sign_up_form[email]' => UsersFixtures::CONFIRMED_USER_EMAIL,
            'sign_up_form[password]' => 'password',
        ]);

        $I->seeCurrentUrlEquals('/signup');
        $I->see('Email already in use.', 'span.form-error-message');
    }


    public function testSignUpOk(FunctionalTester $I)
    {
        $I->amOnPage('/signup');
        $I->seeResponseCodeIs(200);
        $I->submitForm('form[name=sign_up_form]', [
            'sign_up_form[email]' => 'new-user@example.com',
            'sign_up_form[password]' => 'password',
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/');
        $I->see('Registration completed successfully.', 'div.alert-success');

        // User in DB?
        $usersRepository = $I->grabService(UsersRepository::class);
        $newUser = $usersRepository->getOneByEmail('new-user@example.com');
    }
}
