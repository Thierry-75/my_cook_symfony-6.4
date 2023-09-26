<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IngredientTest extends WebTestCase
{
    public function testIfCreateIngredientIsSuccessful(): void
    {
        $client = static::createClient();
        
        $urlGenerator = $client->getContainer()->get('router');
        /** @var EntityManagerInterface $entityManager */ 
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class,1);
        $client->loginUser($user);
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate('ingredient_new'));
        $form = $crawler->filter('form[name=ingredient]')->form([
            "ingredient[name]"=>"prune",
            "ingredient[price]"=> floatval(25)
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', ' a été enregistré !');
        $this->assertRouteSame('app_ingredient');
    }

    public function testIfListingredientIsSuccessfull(): void
    {
        $client = static::createClient();
        
        $urlGenerator = $client->getContainer()->get('router');
        /** @var EntityManagerInterface $entityManager */ 
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class,1);
        $client->loginUser($user);
        $client->request(Request::METHOD_GET, $urlGenerator->generate('app_ingredient'));
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('app_ingredient');
    }

    public function testIfUpdateIngredientIsSuccessfull(): void
    {
        $client  = static::createclient();
        $urlGenerator = $client->getContainer()->get('router');
        /** @var EntityManagerInterface $entityManager */ 
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class,1);
        $ingredient = $entityManager->getRepository(Ingredient::class)->findOneBy(['user'=> $user]);
        $client->loginUser($user);
        $crawler = $client->request(Request::METHOD_GET,$urlGenerator->generate('ingredient_edit',['id'=>$ingredient->getId()]));
        $this->assertResponseIsSuccessful();
        $form = $crawler->filter('form[name=ingredient]')->form([
            "ingredient[name]"=>"lait",
            "ingredient[price]"=> floatval(25)
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', ' a été modifié !');
        $this->assertRouteSame('app_ingredient');
    }

    public function testIfDeleteIngredientIsSucessfull():void
    {
        $client = static::createClient();
        $urlGenerator = $client->getContainer()->get('router');
        /** @var EntityManagerInterface $entityManager */ 
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $user = $entityManager->find(User::class,1);
        $ingredient = $entityManager->getRepository(Ingredient::class)->findOneBy(['user'=>$user]);
        $client->loginUser($user);
        $client->request(Request::METHOD_GET,$urlGenerator->generate('ingredient_delete',['id'=>$ingredient->getId()]));
        //$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        //$client->followRedirect();
        //$this->assertSelectorTextContains('div.alert-success', ' a été supprimé !');
        //$this->assertRouteSame('app_ingredient');
        $this->assertResponseIsSuccessful();
    }
}
