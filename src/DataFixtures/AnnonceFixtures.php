<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTime;

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
                $annonce->setSalaryMin($faker->numberBetween(25000, 35000));
                $annonce->setSalaryMax($faker->numberBetween(40000, 60000));
                $annonce->setContractType($faker->word());
                $annonce->setStudyLevel($faker->word());
                $annonce->setRemote($faker->boolean());
                $annonce->setDescription($faker->paragraphs(3, true));
                $annonce->setWorkTime($faker->numberBetween(15, 39) . "h");
                $annonce->setPublicationStatus($faker->word());
                $annonce->setCreatedAt(new DateTime($faker->date('Y-m-d')));
                $annonce->setOptionalInfo($faker->paragraphs(3, true));
                $annonce->setCompany($this->getReference('company_' . $i));
                $annonce->setAuthor($this->getReference('consultant_' .
                    $faker->numberBetween(1, ExternaticConsultantFixtures::$consultantIndex)));
                for ($a = 1; $a <= 8; $a++) {
                    $annonce->addTechno($this->getReference('techno_' . $faker->unique()->numberBetween(1, 21)));
                }
                $faker->unique(true);
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
            TechnoFixtures::class,
        ];
    }
}
