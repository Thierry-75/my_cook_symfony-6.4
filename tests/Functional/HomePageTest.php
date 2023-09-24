<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $button = $crawler->selectButton('Inscription');
        $this->assertEquals(1,count($button));
        $recettes = $crawler->filter('.recette,.card');
        $this->assertEquals(3,count($recettes));
        $liens = $crawler->filter('a');
        $this->assertEquals(8,count($liens));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Bienvenue');
    }
}
