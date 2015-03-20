<?php namespace Strimoid\Tests\Functional;

use FunctionalTester;

class EntriesCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function showListOfEntries(FunctionalTester $I)
    {
        $I->amOnPage('/g/all/entries');
    }
}
