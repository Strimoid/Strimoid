<?php namespace Strimoid\Tests\Functional;

use FunctionalTester;

class GroupsCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function showListOfGroups(FunctionalTester $I)
    {
        $I->amOnPage('/groups/list');
        $I->canSee('Brocktown', '.panel-title');
        $I->canSee('Nemo ullam aperiam minus consequuntur ipsum.', '.panel-body');
    }
}
