<?php

namespace App\DataFixtures;

use App\Entity\RecruitmentProcess;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class RecruitmentProcessFixtures extends Fixture implements DependentFixtureInterface
{
    public const RECRUIT_STATUS = [
        "Applied",
        "In progress",
        "Completed",
    ];
    public static int $recruitmentIndex = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= AnnonceFixtures::$annonceIndex; $i++) {
            for ($j = 0; $j < $faker->numberBetween(0, 3); $j++) {
                $recruitmentProcess = new RecruitmentProcess();
                self::$recruitmentIndex++;
                $recruitmentProcess->setCreatedAt($faker->dateTimeThisMonth());
                $recruitmentProcess->setAnnonce($this->getReference("annonce_" . $i));
                $recruitmentProcess->setCandidat($this->getReference("candidat_" .
                    $faker->numberBetween(1, CandidatFixtures::$candidatIndex)));
                $recruitmentProcess->setStatus($faker->randomElement(self::RECRUIT_STATUS));
                $this->addReference('recruitmentProcess_' . self::$recruitmentIndex, $recruitmentProcess);
                $manager->persist($recruitmentProcess);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            AnnonceFixtures::class,
            CandidatFixtures::class
        ];
    }
}
