<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LanguageFixtures extends Fixture implements DependentFixtureInterface
{
    public const LANGUAGE = ['Anglais', 'Mandarin', 'Hindi', 'Espagnol', 'Français', 'Arabe', 'Bengali', 'Russe',
        'Portugais', 'Indonésien'];
    public const LEVEL = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= CandidatFixtures::$candidatIndex; $i++) {
            for ($j = 0; $j < 2; $j++) {
                $language = new Language();
                $language->setSkills($this->getReference('skills_' . $i));
                $language->setLanguage(self::LANGUAGE[$faker->numberBetween(0, count(self::LANGUAGE) - 1)]);
                $language->setLevel(self::LEVEL[$faker->numberBetween(0, count(self::LEVEL) - 1)]);
                $manager->persist($language);
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
