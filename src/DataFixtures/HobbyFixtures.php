<?php

namespace App\DataFixtures;

use App\Entity\Hobbie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HobbyFixtures extends Fixture implements DependentFixtureInterface
{
    public const HOBBIES = [
        'écriture', 'bricolage', 'cuisine', 'informatique', 'langue de signes', 'jardinage', 'musique', 'informatique',
        'Yoga', 'Méditation', 'sophrologie', 'photographie', 'magie', 'origami', 'peinture', 'dessin', 'sculpture',
        'calligraphie', 'poterie', 'jeux de société', 'danser', 'randonnée', 'sport', 'golf', 'escalade',
        'jeux vidéo', 'Kayak', 'Aviron', 'Canoë', 'paddle', 'astronomie', 'couture', 'scrapbooking', 'patchwork',
        'tissage', 'cosplay', 'Fabriquer des bijoux', 'Travailler le métal', 'Décorer des galets',
        'puzzles', 'jongler', 'lecture', 'programmation informatique',
        'survivalisme', 'chaine Youtube', 'fléchettes', 'chanter',
        ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= CandidatFixtures::$candidatIndex; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $hobby = new Hobbie();
                $hobby->setCurriculum($this->getReference('curriculum_' . $i));
                $hobby->setTitle(self::HOBBIES[$faker->numberBetween(0, count(self::HOBBIES) - 1)]);
                $manager->persist($hobby);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CandidatFixtures::class,
        ];
    }
}
