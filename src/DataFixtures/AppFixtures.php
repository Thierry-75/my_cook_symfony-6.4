<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;
    
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // ingredient
        $ingredients = [];
        for($i=0; $i <= 50; $i++){
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->sentence(2))
                    ->setPrice($this->faker->randomFloat(2, 10, 200));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient); 
        }
        // recette
        for($j=0; $j < 25; $j++ ){
            $recette = new Recette();
            $recette->setName($this->faker->word())
                ->setTime(mt_rand(0,1) == 1 ? mt_rand(1,1440) : null)
                ->setNbPeople(mt_rand(0,1) == 1 ? mt_rand(1,50) : null)
                ->setDifficulty(mt_rand(0,1) == 1 ? mt_rand(1,5) : null)
                ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(0,1) == 1 ? mt_rand(1,1000) : null)
                ->setIsFavorite(mt_rand(0,1) == 1 ? true : false);
                for($k=0; $k < mt_rand(5,15); $k++){
                        $recette->addIngredient($ingredients[mt_rand(0, count($ingredients) -1)]);
                }
                $manager->persist($recette);
        }

        //user
        for($i = 0; $i < 10; $i ++){
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0,1) === 1 ? $this->faker->firstName(): null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
