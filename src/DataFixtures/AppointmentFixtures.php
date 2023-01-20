<?php

namespace App\DataFixtures;

use App\Entity\Appointement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $appointmentIndex = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= RecruitmentProcessFixtures::$recruitmentIndex; $i++) {
            for ($j = 0; $j < $faker->numberBetween(0, 5); $j++) {
                $appointment = new Appointement();
                self::$appointmentIndex++;
                $appointment->setDescription($faker->sentence);
                $date = $faker->dateTimeBetween('-1 months', '+5 months');
                $date->setTime(intval($faker->time('H')), intval($faker->time('i')));
                $appointment->setDate($date);
                $appointment->setRecruitmentProcess($this->getReference("recruitmentProcess_$i"));
                $appointment->setConsultant($this->getReference("consultant_" .
                    $faker->numberBetween(1, ExternaticConsultantFixtures::$consultantIndex)));
                $manager->persist($appointment);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RecruitmentProcessFixtures::class,
            ExternaticConsultantFixtures::class
        ];
    }
}
