<?php namespace Strimoid\Tests\Functional;

use FunctionalTester;

class AuthCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function loginUsingValidCredentials(FunctionalTester $I)
    {
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->submitForm('.main_col form', [
            'username' => 'tobin74',
            'password' => 'qwe123',
        ]);
        $I->seeAuthentication();
    }

    public function loginUsingInvalidUsername(FunctionalTester $I)
    {
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->submitForm('.main_col form', [
            'username' => 'invalid-username',
            'password' => 'qwe123',
        ]);
        $I->dontSeeAuthentication();
    }

    public function loginUsingInvalidPassword(FunctionalTester $I)
    {
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->submitForm('.main_col form', [
            'username' => 'Karina14',
            'password' => 'invalid-password',
        ]);
        $I->dontSeeAuthentication();
    }

    public function registerNewAccount(FunctionalTester $I)
    {
        $I->dontSeeAuthentication();
        $I->amOnPage('/register');
        $I->submitForm('.main_col form', [
            'username' => 'NewUser',
            'password' => 'qwe123',
            'email'    => 'new@user.com',
        ]);
        $I->seeRecord('users', ['name' => 'NewUser']);
    }

    public function sendPasswordReminder(FunctionalTester $I)
    {
        $I->dontSeeAuthentication();
        $I->amOnPage('/remind');
        $I->submitForm('.main_col form', [
            'email' => 'nigel.trantow@hotmail.com',
        ]);

        // TODO: check if password reminder was sent
    }
}
