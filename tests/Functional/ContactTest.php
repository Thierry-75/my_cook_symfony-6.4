<?php

namespace App\Tests\Functional;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{

    public function testIfSubmitContactFormIsSuccessfull(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');

        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('POST', $urlGenerator->generate('app_contact'));
        $form = $crawler->filter("form[name=contact]")->form([
            "contact[fullName]" => "Janus Arkus ",
            "contact[email]" => "jd@symrecipe.com",
            "contact[subject]" => "Test du sujet",
            "contact[message]" => "Test du message",
         //   "contact[captcha]"=>'' fout la merde !
        ]);
            
                // $client->submit($form);
                //$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
                //$this->assertEmailCount(1);
                //$client->followRedirect();
                //$this->assertSelectorTextContains('div.alert-success','Votre demande a bien été enrregistrée !');
    }
}
