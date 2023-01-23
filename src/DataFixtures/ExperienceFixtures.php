<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ExperienceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= CandidatFixtures::$candidatIndex; $i++) {
            for ($j = 0; $j < 8; $j++) {
                $experience = new Experience();
                $experience->setCurriculum($this->getReference('curriculum_' . $i));
                $experience->setBeginning($faker->dateTimeBetween('-20 years', '-5 years'));
                $experience->setEnd($faker->dateTimeBetween('-5 years', 'now'));
                $experience->setTitle($faker->lexify('FormExp-?????'));
                $experience->setOrganism($faker->lexify('Org-?????'));
                $experience->setLocation($faker->city());
                $experience->setDescription('Desc :' . $faker->paragraph());
                $experience->setIsFormation($faker->boolean());
                $experience->setDiploma($faker->lexify('diplome-?????'));
                $manager->persist($experience);
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
