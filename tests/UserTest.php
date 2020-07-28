<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{

    public static $createClient;

    public static function setUpBeforeClass()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "name":"Fabien24",
                "email": "jose@dasilva.com.br",
                "telephones": [
                    {
                        "number": "333333"
                     },
                     {
                       "number": "444444"
                     }
                ]
            }'
        );
        self::$createClient = $client;
    }


    public function test_CreateUser(): void
    {
        $this->assertEquals(201, self::$createClient->getResponse()->getStatusCode());
    }

    public function test_DetailUser(): void
    {
        $location = self::$createClient->getResponse()->headers->get('location');
        $client2 = static::createClient();
        $client2->request('GET', $location);
        $this->assertEquals(200, $client2->getResponse()->getStatusCode());
    }

    public function test_UpdateUser(): void
    {
        $location = self::$createClient->getResponse()->headers->get('location');
        $client2 = static::createClient();
        $client2->request(
            'PUT',
            $location,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{
                "name":"Fabien_alt",
                "email": "jose_alterado@dasilva.com.br",
                "telephones": [
                    {
                        "number": "23234344"
                     },
                     {
                       "number": "234234234"
                     }
                ]
            }'
        );
        $this->assertEquals(200, $client2->getResponse()->getStatusCode());
    }

    public function test_GetAllUsers(): void
    {
        $client2 = static::createClient();
        $client2->request('GET', '/users');
        $this->assertEquals(200, $client2->getResponse()->getStatusCode());
    }

    public function test_removeUsers(): void
    {
        $location = self::$createClient->getResponse()->headers->get('location');
        $client2 = static::createClient();
        $client2->request('DELETE', $location);
        $this->assertEquals(200, $client2->getResponse()->getStatusCode());
    }
}
