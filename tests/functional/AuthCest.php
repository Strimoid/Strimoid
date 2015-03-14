<?php

use \FunctionalTester;

class AuthCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function loginUsingCredentials(FunctionalTester $I)
    {
        $I->dontSeeAuthentication();
        $I->amOnPage('/login');
        $I->submitForm('.main_col form', [
            'username' => 'Karina14',
            'password' => 'qwe123',
        ]);
        $I->seeAuthentication();
    }

    public function registerNewAccount(FunctionalTester $I)
    {
        $I->amOnPage('/register');
        $I->submitForm('.main_col form', [
            'username' => 'NewUser',
            'password' => 'qwe123',
            'email'    => 'new@user.com',
        ]);
        $I->seeRecord('users', ['name' => 'NewUser']);
    }
}
