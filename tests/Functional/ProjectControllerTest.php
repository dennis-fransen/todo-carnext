<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    public function testProjectIndexList()
    {
        $client = static::createClient();
        $client->request('GET', '/projects');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data[0]['title'] == 'project title');
    }

    public function testGetSingleProject()
    {
        $client = static::createClient();

        $client->request('GET', '/project/1');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue($data['title'] == 'project title');
    }

    public function testCreateProject()
    {
        $client = static::createClient();

        $postData =
            [
                'title' => 'test',
            ];

        $client->request(
            'POST',
            'project',
            $postData,
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $client->request('POST', '/project', $postData, [],
                         ['CONTENT_TYPE' => 'application/json']);

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(array_key_exists('project_id', $data));
    }

    public function testInvalidCreateProject()
    {
        $client = static::createClient();

        $postData =
            [
                'description' => 'test',
            ];

        $client->request(
            'POST',
            'project',
            $postData,
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertResponseStatusCodeSame(500);

        $this->assertSelectorTextContains('body', 'This value should not be blank');
    }

    public function testDeleteProject()
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            'project/2'
        );

        $this->assertResponseStatusCodeSame(204);

    }

    public function testInvalidDeleteProject()
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            'project/1'
        );

        $this->assertResponseStatusCodeSame(500);
        $this->assertSelectorTextContains('body', 'projects with tasks cannot be deleted');


    }
}
