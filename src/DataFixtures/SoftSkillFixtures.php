<?php

namespace App\DataFixtures;

use App\Entity\SoftSkill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SoftSkillFixtures extends Fixture implements DependentFixtureInterface
{
    public const SOFTSKILLS = ['résolution de problèmes', 'confiance', 'L’empathie', 'communication',
        'gestion du temps', 'gestion du stress', 'créativité', 'esprit d’entreprendre', 'L’audace', 'motivation',
        'Vision', 'visualisation', 'présence', 'sens du collectif', 'curiosité', 'Adaptabilité', 'Autonomie',
        'Esprit d’initiative', 'Ethique', 'Humilité', 'Optimisme','Persévérance', 'Résilience', 'Leadership',
        'Résolution de conflit'];


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= CandidatFixtures::$candidatIndex; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $softskill = new SoftSkill();
                $softskill->setSkills($this->getReference('skills_' . $i));
                $softskill->setName(self::SOFTSKILLS[$faker->numberBetween(0, count(self::SOFTSKILLS) - 1)]);
                $manager->persist($softskill);
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
