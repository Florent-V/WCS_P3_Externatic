<?php

namespace App\DataFixtures;

use App\Entity\HardSkill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HardSkillFixtures extends Fixture implements DependentFixtureInterface
{
    public const HARDSKILLS = ['C', 'C++', 'C#', 'CSS', 'Delphi', 'Go', 'HTML', 'Java', 'JavaScript', 'Lua',
        'Matlab', 'MySQL', 'ObjC', 'Perl', 'PHP', 'Python', 'R', 'Ruby', 'Swift', 'VisualBasic', 'VisualBasicClassic',
        'TDD', 'AWS', 'Linux', 'Agile'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= CandidatFixtures::$candidatIndex; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $hardSkill = new HardSkill();
                $hardSkill->setSkills($this->getReference('skills_' . $i));
                $hardSkill->setName(self::HARDSKILLS[$faker->numberBetween(0, count(self::HARDSKILLS) - 1)]);
                $manager->persist($hardSkill);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CandidatFixtures::class,
        ];
    }
}
