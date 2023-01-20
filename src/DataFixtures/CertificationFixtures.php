<?php

namespace App\DataFixtures;

use App\Entity\Certification;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CertificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= CandidatFixtures::$candidatIndex; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $certification = new Certification();
                $certification->setCurriculum($this->getReference('curriculum_' . $i));
                $certification->setTitle($faker->lexify('certif-?????'));
                $certification->setYear($faker->dateTime());
                $certification->setLink('https://www.udemy.com/fr/');
                $certification->setDescription('Description : ' . $faker->paragraph());
                $manager->persist($certification);
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
