<?php namespace Strimoid\Tests\Api;

use ApiTester;

class ContentsCest
{

    protected $endpoint = '/api/v1/contents';

    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function getListOfContents(ApiTester $I)
    {
        $I->sendGET($this->endpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

}