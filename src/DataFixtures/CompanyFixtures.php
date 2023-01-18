<?php

namespace App\DataFixtures;

use App\DataFixtures\ExternaticConsultantFixtures;
use App\Entity\Company;
use App\Entity\ExternaticConsultant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public static int $companyIndex = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= ExternaticConsultantFixtures::$consultantIndex; $i++) {
            $nbCompanies = 10;
            for ($j = 1; $j <= $nbCompanies; $j++) {
                $company = new Company();
                self::$companyIndex++;
                $company->setSiret($faker->siret());
                $company->setName("Entreprise-" . self::$companyIndex);
                $company->setLogo('https://fakeimg.pl/200x200/?text=logo');
                $company->setZipCode($faker->departmentNumber() . $faker->numberBetween(100, 900));
                $company->setAddress($faker->streetAddress);
                $company->setCity($faker->city());
                $company->setPhoneNumber($faker->phoneNumber());
                $company->setContactName($faker->name());
                $company->setSize($faker->numberBetween(10, 100000));
                $company->setInformation("info-entr./ " . $faker->paragraphs(2, true));
                $company->setExternaticConsultant($this->getReference('consultant_' . $i));
                $manager->persist($company);
                $this->addReference('company_' . self::$companyIndex, $company);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ExternaticConsultantFixtures::class,
        ];
    }
}
