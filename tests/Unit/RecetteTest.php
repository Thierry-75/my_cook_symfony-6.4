<?php

namespace App\Tests\Unit;

use App\Entity\Recette;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
class RecetteTest extends KernelTestCase
{
    public function testSomething(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $recette = new Recette();
        $recette->setName('Recette #1')
            ->setDescription('Description #1')
            ->setIsFavorite(true)
            ->setCreateAt(new \DateTimeImmutable())
            ->setUpdateAt(new \DateTimeImmutable());
        $errors = $container->get('validator')->validate($recette);
        $this->assertCount(0, $errors);





    }
}
