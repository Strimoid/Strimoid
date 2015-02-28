<?php

use \FunctionalTester;

class AuthCest {

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
        $I->amLoggedAs(['name' => 'Karina14']);
    }

}