<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        
        // get route by urlgenerator
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('app_security'));
        // manage form
        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "admin@cook.fr",
            "_password" => "password"
        ]);

        $client->submit($form);
        // Redirect + home
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertRouteSame('app_main');
    }

    public function testIfLoginFailedWhenPasswordIsWrong(): void
    {
        $client = static::createClient();
        
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('app_security'));
        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "admin@cook.fr",
            "_password" => "password_"
        ]);

        $client->submit($form);
         $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
         $client->followRedirect();
         $this->assertRouteSame('app_security');
         $this->assertSelectorTextContains('div.alert-danger','Identifiants invalides.');
    }
}
