<?php

namespace App\Tests;

use App\Core\Repositories\UsersRepository;
use App\DataFixtures\UsersFixtures;

class SignUpControllerCest
{

    public function testSignUpOk(FunctionalTester $I)
    {
        $I->amOnPage('/signup');
        $I->seeResponseCodeIs(200);
        $I->seeElement('form', ['name' => 'sign_up_form']);
        $I->fillField('First name', $firstName = 'Kelly');
        $I->fillField('Last name', $lastName = 'Wilson');
        $I->fillField('Email', $email = 'new-user@example.com');
        $I->fillField('Password', 'password');
        $I->click('Sign up', '.btn');

        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/');
        $I->see('Registration completed successfully.', 'div.alert-success');

        // User in DB?
        $usersRepository = $I->grabService(UsersRepository::class);
        $newUser = $usersRepository->getOneBy([
            'email' => $email,
            'name.firstName' => $firstName,
            'name.lastName' => $lastName,
        ]);
        $I->assertFalse($newUser->getStatus()->isActive());
    }


    public function testSignUpWithInvalidCredentials(FunctionalTester $I)
    {
        $I->amOnPage('/signup');
        $I->submitForm('form[name=sign_up_form]', [
            'sign_up_form[firstName]' => 'Kelly',
            'sign_up_form[lastName]' => 'Wilson',
            'sign_up_form[email]' => 'not-valid-email',
            'sign_up_form[password]' => '123',
        ]);

        $I->seeCurrentUrlEquals('/signup');
        $I->seeNumberOfElements('input.is-invalid', 2);
        $I->seeElement('input#sign_up_form_email.is-invalid');
        $I->seeElement('input#sign_up_form_password.is-invalid');
    }


    public function testSignUpWithExistsEmail(FunctionalTester $I)
    {
        $I->amOnPage('/signup');
        $I->submitForm('form[name=sign_up_form]', [
            'sign_up_form[firstName]' => 'Kelly',
            'sign_up_form[lastName]' => 'Wilson',
            'sign_up_form[email]' => UsersFixtures::CONFIRMED_USER_EMAIL,
            'sign_up_form[password]' => 'password',
        ]);

        $I->seeCurrentUrlEquals('/signup');
        $I->see('Email already in use.', 'span.form-error-message');
    }


    public function testSignUpConfirm(FunctionalTester $I)
    {
        $I->amOnPage('/signup/confirm/' . UsersFixtures::UNCONFIRMED_USER_TOKEN);
        $I->seeResponseCodeIs(200);
        $I->seeCurrentUrlEquals('/');
        $I->see('Email is successfully confirmed.', 'div.alert-success');

        $usersRepository = $I->grabService(UsersRepository::class);
        $user = $usersRepository->getOneByEmail(UsersFixtures::UNCONFIRMED_USER_EMAIL);
        $I->assertTrue($user->getStatus()->isActive());
    }
}
