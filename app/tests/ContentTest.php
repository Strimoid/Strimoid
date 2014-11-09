<?php

class ContentTest extends TestCase {

    public function testList()
    {
        // All contents
        $response = $this->call('GET', 'api/v1/contents');
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);
    }

    public function testListWithGroupFilter()
    {
        // Contents from selected group
        $groupIds = DB::collection('groups')->lists('urlname');
        $randomGroup = $groupIds[array_rand($groupIds)];

        $response = $this->call('GET', 'api/v1/contents', ['group' => $randomGroup]);
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);
    }

    public function testGetContentData()
    {
        // Get random content
        $response = $this->call('GET', 'api/v1/contents/'. $this->randomId('contents'));
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('title', $content);
    }

    public function testAddEmptyContent()
    {
        // Try to add content without required fields
        $this->be(User::first());

        $this->call('POST', 'api/v1/contents', [
            'title' => '',
            'url' => '',
        ]);

        $this->assertResponseStatus(400);
    }

    public function testAddInvalidContent()
    {
        // Try to add content with too long text
        $this->be(User::first());

        $this->call('POST', 'api/v1/contents', [
            'title' => $this->faker->text(200),
            'description' => $this->faker->text(500),
            'url' => $this->faker->word,
            'group' => $this->faker->word,
        ]);

        $this->assertResponseStatus(400);
    }

    public function testAddLinkContent()
    {
        // Try to add content with too long text
        $this->be(User::first());

        $response = $this->call('POST', 'api/v1/contents', [
            'title' => $this->faker->text(64),
            'description' => $this->faker->text(128),
            'url' => $this->faker->url,
            'group' =>$this->randomField('groups', 'urlname'),
        ]);

        $this->assertResponseStatus(200);
    }

}
