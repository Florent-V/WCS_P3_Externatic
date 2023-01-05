<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $messageIndex = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= $faker->numberBetween(50, 100); $i++) {
            //Attachement à un processus de recrutement
            $recruitmentProcess = null;
            if ($faker->boolean()) {
                $recruitmentProcess = "recruitmentProcess_" .
                    $faker->numberBetween(1, RecruitmentProcessFixtures::$recruitmentIndex);
            }

            //Choix des destinataires et du premier envoyeur
            $candidat = 'userCandidat_' . $faker->numberBetween(1, UserFixtures::$userCandidatIndex);
            $externaticConsultant = 'userConsultant_' . $faker->numberBetween(1, UserFixtures::$userConsultantIndex);
            $isSendByCandidat = $faker->boolean();


            for ($j = 0; $j < $faker->numberBetween(1, 5); $j++) {
                self::$messageIndex++;
                $message = new Message();
                $message->setDate($faker->dateTimeThisYear('+10 months'));
                $message->setContent($faker->paragraph);
                if (!is_null($recruitmentProcess)) {
                    $message->setRecruitmentProcess($this->getReference($recruitmentProcess));
                }
                if ($isSendByCandidat) {
                    $message->setSendBy($this->getReference($candidat));
                    $message->setSendTo($this->getReference($externaticConsultant));
                } else {
                    $message->setSendBy($this->getReference($externaticConsultant));
                    $message->setSendTo($this->getReference($candidat));
                }

                $manager->persist($message);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CandidatFixtures::class,
            RecruitmentProcessFixtures::class
        ];
    }
}
