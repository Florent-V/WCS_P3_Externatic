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

        for ($i = 1; $i <= 600; $i++) {
            //Attachement à un processus de recrutement
            $recruitmentProcess = null;
                $recruitmentProcess = "recruitmentProcess_" .
                    $faker->numberBetween(1, RecruitmentProcessFixtures::$recruitmentIndex);

            //Choix des destinataires et du premier envoyeur
            $isSendByCandidat = $faker->boolean();


            for ($j = 0; $j < $faker->numberBetween(1, 5); $j++) {
                self::$messageIndex++;
                $message = new Message();
                $date = $faker->dateTimeBetween('-1 year', 'now');
                $date->setTime(intval($faker->time('H')), intval($faker->time('i')), intval($faker->time('s')));
                $message->setDate($date);
                $message->setTitle("mesTitle/" . $faker->sentence(3));
                $message->setContent("Contenu : " . $faker->paragraph);

                if ($isSendByCandidat) {
                        $message->setRecruitmentProcess($this->getReference($recruitmentProcess));
                        $message->setSendBy($message->getRecruitmentProcess()->getCandidat()->getUser());
                    if (!is_null($message->getRecruitmentProcess()->getAnnonce())) {
                        $message->setSendTo($message->getRecruitmentProcess()->getAnnonce()
                            ->getAuthor()->getUser());
                    } else {
                        $message->setSendTo($message->getRecruitmentProcess()->getCompany()
                            ->getExternaticConsultant()->getUser());
                    }
                } else {
                        $message->setRecruitmentProcess($this->getReference($recruitmentProcess));
                        $message->setSendTo($message->getRecruitmentProcess()->getCandidat()->getUser());
                    if (!is_null($message->getRecruitmentProcess()->getAnnonce())) {
                        $message->setSendBy($message->getRecruitmentProcess()->getAnnonce()
                            ->getAuthor()->getUser());
                    } else {
                        $message->setSendBy($message->getRecruitmentProcess()->getCompany()
                            ->getExternaticConsultant()->getUser());
                    }
                }

                $isSendByCandidat = !$isSendByCandidat;
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
