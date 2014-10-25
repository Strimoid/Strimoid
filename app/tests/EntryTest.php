<?php

class EntryTest extends TestCase {

    /**
     * Test entries listing.
     *
     * @return void
     */
    public function testList()
    {
        // All contents
        $response = $this->call('GET', '/api/v1/entries');
        $content = json_decode($response->getContent());

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);

        // Contents from selected group
        $groupIds = DB::collection('groups')->lists('_id');

        $response = $this->call('GET', '/api/v1/entries?group='. array_rand($groupIds));
        $content = json_decode($response->getContent());

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);
    }

}
