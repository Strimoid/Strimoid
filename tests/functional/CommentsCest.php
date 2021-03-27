<?php namespace Strimoid\Tests\Functional;

use FunctionalTester;

class CommentsCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function showListOfComments(FunctionalTester $I)
    {
        $I->amOnPage('/g/all/comments');
        $I->canSeeElement('.comment_text');
    }

}
