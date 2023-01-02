<?php

namespace App\DataFixtures;

use App\Entity\RecruitmentProcess;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class RecruitmentProcessFixtures extends Fixture
{
    public const RECRUIT_STATUS = [
        "Applied",
        "In progress",
        "Completed",
    ];
    public static int $recruitmentProcessIndex = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $recruitmentProcess = new RecruitmentProcess();
        self::$recruitmentProcessIndex++;
        $recruitmentProcess->setCreatedAt($faker->dateTimeThisMonth());
        $recruitmentProcess->setAnnonce($this->getReference("annonce_" .
            $faker->numberBetween(1, AnnonceFixtures::$annonceIndex)));
        $recruitmentProcess->setCandidat($this->getReference("userCandidat_") .
            $faker->numberBetween(1, CandidatFixtures::$candidatIndex));
        $recruitmentProcess->setStatus(self::RECRUIT_STATUS[array_rand(self::RECRUIT_STATUS)]);
        $this->addReference('recruitmentProcess_' . self::$recruitmentProcessIndex);
        $manager->persist($recruitmentProcess);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            AnnonceFixtures::class,
        ];
    }
}
