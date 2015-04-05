<?php namespace Strimoid\Tests\Functional;

use FunctionalTester;
use Strimoid\Models\User;

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
        $I->canSee('Tedville', '.panel-title');
        $I->canSee('Commodi pariatur numquam', '.panel-body');
    }

    public function createNewGroup(FunctionalTester $I)
    {
        $I->amLoggedAs(User::first());
        $I->amOnPage('/groups/list');
        $I->click('Załóż nową grupę');
        $I->submitForm('.main_col form', [
            'urlname'     => 'NewGroup',
            'groupname'   => 'New group',
            'description' => 'Example description',
        ]);
    }
}
