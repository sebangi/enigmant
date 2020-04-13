<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Chene\JeuEnChene;
use Faker\Factory;

class JeuEnCheneFixture extends Fixture {

    public function load(ObjectManager $manager) {

        $faker = Factory::create("fr_FR");

        for ($i = 0; $i != 100; $i++) {
            $jeuEnChene = new JeuEnChene();

            $jeuEnChene
                    ->setIntitule($faker->words(3, true))
                    ->setCommentaire($faker->sentences(3, true))
                    ->setDisponible(true)
                    ->setDifficulteObservation($faker->numberBetween(0, 10))
                    ->setDifficulteRaisonnement($faker->numberBetween(0, 10))
                    ->setTempsLocation($faker->numberBetween(0, count(\App\Entity\Chene\JeuEnChene::codeLocation) -1 ))
                    ->setPrix($faker->numberBetween(1, 10));

            $manager->persist($jeuEnChene);
        }

        $manager->flush();
    }

}
