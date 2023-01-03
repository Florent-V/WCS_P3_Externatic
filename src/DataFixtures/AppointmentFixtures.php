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
            for ($j = 0; $j < $faker->numberBetween(0, 2); $j++) {
                $appointment = new Appointement();
                self::$appointmentIndex++;
                $appointment->setDescription($faker->sentence);
                $appointment->setDate($faker->dateTimeThisYear('+10 months'));
                $appointment->setRecruitmentProcess($this->getReference("recruitmentProcess_$i"));
                $manager->persist($appointment);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RecruitmentProcessFixtures::class,
        ];
    }
}
