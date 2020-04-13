<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Chene\Babiole;
use Faker\Factory;

class BabioleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        for ($i = 0; $i != 10; $i++) {
            $babiole = new Babiole();

            $babiole
                    ->setNom($faker->words(1, true))
                    ->setDescription($faker->sentences(3, true))
                    ->setValeur($faker->numberBetween(0, 10))
                    ->setCommentaireGourou($faker->sentences(3, true));

            $manager->persist($babiole);
        }

        $manager->flush();
    }
}
