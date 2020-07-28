<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    public function test_GetAllUsers(): void
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function test_CreateUser(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "name":"Fabien",
                "email": "jose@dasilva.com.br"
            }'
        );
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
