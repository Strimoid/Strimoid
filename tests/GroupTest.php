<?php

class GroupTest extends TestCase
{

    public function testList()
    {
        // All contents
        $response = $this->call('GET', 'api/v1/groups');
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('data', $content);
    }

    public function testGetGroupData()
    {
        // Get random content
        $response = $this->call('GET', 'api/v1/groups/' . $this->randomGroup());
        $content = json_decode($response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey('name', $content);
    }

}
