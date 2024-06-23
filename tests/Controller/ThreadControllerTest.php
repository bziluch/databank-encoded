<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ThreadControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        parent::setUp();
    }

    public function testExistingThreadView(): void
    {
        $threadId = 1;

        $this->client->request('GET', '/thread/view/'.$threadId);
        $this->assertResponseStatusCodeSame(200);
    }

    public function testNonExistingThreadView(): void
    {
        // non-existing entity id
        $threadId = 12345678;

        $this->client->request('GET', '/thread/view/'.$threadId);
        $this->assertResponseStatusCodeSame(404);
    }
}
