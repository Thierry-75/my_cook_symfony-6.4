<?php

namespace App\tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BasicTest extends WebTestCase
{
    public function testBasicFunctional(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue(true);
        $this->assertResponseIsSuccessful();
        $this->assertBrowserNotHasCookie(true);

    }
}
