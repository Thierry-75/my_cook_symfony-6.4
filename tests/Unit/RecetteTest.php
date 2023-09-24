<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Recette;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecetteTest extends KernelTestCase
{

    public function getEntity(): Recette
    {
        return (new Recette())->setName('Recette #1')
        ->setDescription('Description #1')
        ->setIsFavorite(true)
        ->setCreateAt(new \DateTimeImmutable())
        ->setUpdateAt(new \DateTimeImmutable());
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $recette = $this->getEntity();
        
        $errors = $container->get('validator')->validate($recette);
        $this->assertCount(0, $errors);
    }

    public function testinvalidName(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $recette = $this->getEntity();
        $recette->setName('');
        $errors = $container->get('validator')->validate($recette);
        $this->assertCount(2, $errors);                                     //notblank,min2

    }

    public function getAverage(): void
    {
        $recette =$this->getEntity();
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class,1);

        for($i=0; $i <5; $i++){
            $mark = new Mark();
            $mark->setMark(2)
                ->setUser($user)
                ->setRecette($recette);
            $recette->addMark($mark);
        }
        $this->assertTrue(2.0 === $recette->getAverage());
    }
}
