<?php

namespace App\DataFixtures;

use App\Entity\Notif;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NotifFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i < 20; $i++) {
            for ($u = 1; $u <= count(UserFixtures::CANDIDAT_INFOS); $u++) {
                    $notif = new Notif();
                    $notif->setContent(['title' => $this->getReference('annonce_' . $i)->getTitle()]);
                    $notif->setType('newAnnonce');
                    $notif->setCreatedAt($faker->dateTimeBetween('-2 week'));
                    $notif->setUser($this->getReference('userCandidat_' . $u));
                    $notif->setParameter($this->getReference('annonce_' . $i)->getId());
                    $notif->setWasRead(false);
                    $manager->persist($notif);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AnnonceFixtures::class,
        ];
    }
}
