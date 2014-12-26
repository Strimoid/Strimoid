<?php

use Strimoid\Models\User;

class EntryTest extends TestCase {

    public function testList()
    {
        // All contents
        $response = $this->call('GET', 'api/v1/entries');
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);
    }

    public function testListWithGroupFilter()
    {
        // Contents from selected group
        $response = $this->call('GET', 'api/v1/entries', ['group' => $this->randomGroup()]);
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);
    }

    public function testGetEntryData()
    {
        // Get random entry
        $response = $this->call('GET', 'api/v1/entries/'. $this->randomId('entries'));
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('text', $content);
    }

    public function testAddEmptyEntry()
    {
        // Try to add entry without required fields
        $this->be(User::first());

        $this->call('POST', 'api/v1/entries', [
            'text' => '',
            'group' => '',
        ]);

        $this->assertResponseStatus(400);
    }

    public function testAddInvalidEntry()
    {
        // Try to add entry without required fields
        $this->be(User::first());

        $this->call('POST', 'api/v1/entries', [
            'text' => $this->faker->text(5000),
            'group' => $this->faker->word,
        ]);

        $this->assertResponseStatus(400);
    }

    public function testAddEntry()
    {
        // Try to add entry without required fields
        $this->be(User::first());

        $this->call('POST', 'api/v1/entries', [
            'text' => $this->faker->text(500),
            'group' => $this->randomGroup(),
        ]);

        $this->assertResponseStatus(200);
    }

}
