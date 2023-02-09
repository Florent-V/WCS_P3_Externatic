<?php

namespace App\DataFixtures;

use App\Entity\Candidat;
use App\Entity\Curriculum;
use App\Entity\Skills;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class CandidatFixtures extends Fixture implements DependentFixtureInterface
{
    public const CANDIDATS = [
        [
            'age' => 25,
            'linked_in' => 'https://www.linkedin.com/candidat1',
            'github' => 'https://www.github.com/LoveCode',
            'zip_code' => '33789',
            'address' => '19 Rue Pommier',
            'city' => 'Metz',
            'description' => 'Bonjour, après plusieurs années en tant que boulanger,
            je me réoriente',
            'can_postulate' => true,
        ],
        [
            'age' => 38,
            'linked_in' => 'https://www.linkedin.com/candidat2',
            'github' => 'https://www.github.com/LoveCyber',
            'zip_code' => '91000',
            'address' => '74 Impasse Jean De la Fontaine',
            'city' => 'Marmande',
            'description' => 'Bonjour, Je suis DevOps',
            'can_postulate' => false,
        ],
        [
            'age' => 75,
            'linked_in' => 'https://www.linkedin.com/candidat3',
            'github' => 'https://www.github.com/LoveHTML',
            'zip_code' => '98765',
            'address' => '74 Boulevard François Pérusse',
            'city' => 'Brest',
            'description' => 'Salut, Je suis SCRUM Master et LeadDev',
            'can_postulate' => true,
        ],
        [
            'age' => 39,
            'linked_in' => 'https://www.linkedin.com/candidat4',
            'github' => 'https://www.github.com/PHP>JS',
            'zip_code' => '77000',
            'address' => '74 Place Vendaume',
            'city' => 'Paris',
            'description' => 'Salut, Ma spécialité c\'est le .NET et Java' ,
            'can_postulate' => true,
        ],
        [
            'age' => 18,
            'linked_in' => 'https://www.linkedin.com/candidat5',
            'github' => 'https://www.github.com/Wild>Harvard',
            'zip_code' => '95000',
            'address' => '74 Rue D\'Artagnan',
            'city' => 'Le Havre',
            'description' => 'Bonjour , je travaillais pour le gouvernement en tant que hacker éthique',
            'can_postulate' => true,
        ],
        [
            'age' => 28,
            'linked_in' => 'https://www.linkedin.com/in/e-riff/',
            'github' => 'https://www.github.com/e-riff',
            'zip_code' => '95080',
            'address' => '74 Rue Jean-François Morin',
            'city' => 'Pau',
            'description' => 'Bonjour, Je suis actuellement dev web chez Externatic et je souhaite me réorienter',
            'can_postulate' => true,

        ],


    ];
    public static int $candidatIndex = 0;
    public function load(ObjectManager $manager): void
    {
        foreach (self::CANDIDATS as $candidatinf) {
            self::$candidatIndex++;
            $candidat = new Candidat();
            $curriculum = new Curriculum();
            $candidat->setAge($candidatinf['age']);
            $candidat->setLinkedIn($candidatinf['linked_in']);
            $candidat->setGithub($candidatinf['github']);
            $candidat->setZipCode($candidatinf['zip_code']);
            $candidat->setAddress($candidatinf['address']);
            $candidat->setCity($candidatinf['city']);
            $candidat->setDescription($candidatinf['description']);
            $candidat->setCanPostulate($candidatinf['can_postulate']);
            $candidat->setCurriculum($curriculum);
            $candidat->setUser($this->getReference('userCandidat_' . self::$candidatIndex));
            $skills = new Skills();
            $skills->setCurriculum($curriculum);
            $this->addReference('curriculum_' . self::$candidatIndex, $curriculum);
            $this->addReference('candidat_' . self::$candidatIndex, $candidat);
            $this->addReference('skills_' . self::$candidatIndex, $skills);
            $manager->persist($candidat);
            $manager->persist($skills);
            $manager->persist($curriculum);
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
