<?php

namespace App\DataFixtures;

use App\DataFixtures\UserFixtures;
use App\Entity\ExternaticConsultant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExternaticConsultantFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $consultantIndex = 0;
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= UserFixtures::$userConsultantIndex; $i++) {
            $consultant = new ExternaticConsultant();
            self::$consultantIndex++;
            $consultant->setUser($this->getReference('userConsultant_' . $i));
            $manager->persist($consultant);
            $this->addReference('consultant_' . self::$consultantIndex, $consultant);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
