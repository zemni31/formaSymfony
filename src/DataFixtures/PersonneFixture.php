<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PersonneFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $jobs = [
            'Ingénieur',
            'Professeur',
            'Médecin',
            'Développeur',
            'Commercial',
            'Architecte',
            'Designer',
            'Chef de projet',
            'Technicien',
            'Consultant'
        ];

        // Récupère toutes les personnes avec job null
        $personnesSansJob = $manager->getRepository(Personne::class)->findBy(['job' => null]);

        foreach ($personnesSansJob as $personne) {
            $personne->setJob($faker->randomElement($jobs));
            // Pas besoin de persist, car entités déjà suivies par Doctrine
        }

        $manager->flush();
    }
}
