<?php namespace Strimoid\Tests\Functional;

use FunctionalTester;
use Strimoid\Models\User;

class ContentsCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function showListOfNewContents(FunctionalTester $I)
    {
        $I->amOnPage('/g/all/new');
        $I->canSee('Deserunt voluptas aut', '.media-heading');
    }

    public function addLink(FunctionalTester $I)
    {
        $I->amLoggedAs(User::first());
        $I->amOnPage('/');
        $I->click('Add content');
        $I->submitForm('.main_col form', [
            'groupname'   => 'weimann',
            'url'         => 'http://strimoid.dev',
            'title'       => 'New content',
            'description' => 'Example description'
        ]);
        $I->seeRecord('contents', ['title' => 'New content']);
    }
}
