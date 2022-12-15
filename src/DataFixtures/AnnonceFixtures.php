<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AnnonceFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $annonceIndex = 0;

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= CompanyFixtures::$companyIndex; $i++) {
            $nbAnnonce = 5;
            for ($j = 1; $j <= $nbAnnonce; $j++) {
                $annonce = new Annonce();
                self::$annonceIndex++;
                $annonce->setTitle($faker->sentence(3));
                $annonce->setPicture('https://fakeimg.pl/200x200/?text=picture');
                $annonce->setSalary($faker->numberBetween(25000, 60000));
                $annonce->setContractType($faker->word());
                $annonce->setStudyLevel($faker->word());
                $annonce->setRemote($faker->boolean());
                $annonce->setDescription($faker->paragraphs(3, true));
                $annonce->setWorkTime($faker->numberBetween(15, 39));
                $annonce->setPublicationStatus($faker->word());
                $annonce->setCreatedAt(new \DateTime($faker->date('Y-m-d')));
                $annonce->setCompany($this->getReference('company_' . $i));
                $annonce->setAuthor($this->getReference('consultant_' . $faker->numberBetween(1, ExternaticConsultantFixtures::$consultantIndex)));
                $manager->persist($annonce);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ExternaticConsultantFixtures::class,
            CompanyFixtures::class,
        ];
    }
}
