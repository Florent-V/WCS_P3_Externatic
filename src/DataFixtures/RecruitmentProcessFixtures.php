<?php

namespace App\DataFixtures;

use App\Entity\RecruitmentProcess;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class RecruitmentProcessFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $recruitmentIndex = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= AnnonceFixtures::$annonceIndex; $i++) {
            for ($j = 0; $j < $faker->numberBetween(0, 3); $j++) {
                $recruitmentProcess = new RecruitmentProcess();
                self::$recruitmentIndex++;
                $recruitmentProcess->setCreatedAt($faker->dateTimeThisMonth());
                if ($faker->boolean()) {
                    $annonceRef = "annonce_" . $i;
                    $recruitmentProcess->setAnnonce($this->getReference($annonceRef));
                    $recruitmentProcess->setExternaticConsultant($recruitmentProcess->getAnnonce()->getAuthor());
                } else {
                    $companyRef = "company_" . $faker->numberBetween(1, CompanyFixtures::$companyIndex);
                    $recruitmentProcess->setCompany($this->getReference($companyRef));
                    $recruitmentProcess->setExternaticConsultant(
                        $recruitmentProcess->getCompany()->getExternaticConsultant()
                    );
                }
                $recruitmentProcess->setRate($faker->numberBetween(1, 5));
                $recruitmentProcess->setCandidat($this->getReference("candidat_" .
                    $faker->numberBetween(1, CandidatFixtures::$candidatIndex)));
                $recruitmentProcess->setReadByCandidat($faker->boolean);
                $recruitmentProcess->SetReadByConsultant($faker->boolean);
                $recruitmentProcess->setStatus($faker->randomElement(RecruitmentProcess::RECRUIT_STATUS));
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
            CandidatFixtures::class,
            CompanyFixtures::class,
        ];
    }
}
