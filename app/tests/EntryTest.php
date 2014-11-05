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
        $response = $this->call('GET', 'api/v1/entries');
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);

        // Contents from selected group
        $groupIds = DB::collection('groups')->lists('urlname');
        $randomGroup = $groupIds[array_rand($groupIds)];

        $response = $this->call('GET', 'api/v1/entries', ['group' => $randomGroup]);
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);
    }

}
