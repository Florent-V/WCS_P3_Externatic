<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public static int $userConsultantIndex = 0;
    public static int $userCandidatIndex = 0;

    public const CONSULTANT_INFOS = [
        [
            'email' => 'admin@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'admin',
            'lastname' => 'externatic',
            'role' => 'ROLE_ADMIN'
        ],
        [
            'email' => 'consultant1@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'consultant1',
            'lastname' => 'externatic',
            'role' => 'ROLE_CONSULTANT'
        ],
        [
            'email' => 'consultant2@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'consultant2',
            'lastname' => 'externatic',
            'role' => 'ROLE_CONSULTANT'
        ]
    ];

    public const CANDIDAT_INFOS = [

        [
            'email' => 'candidat1@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'Victorine',
            'lastname' => 'Beaulieu',
            'role' => 'ROLE_CANDIDAT',
        ],
        [
            'email' => 'candidat2@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'Stéphane',
            'lastname' => 'externatic',
            'role' => 'ROLE_CANDIDAT',
        ],
        [
            'email' => 'candidat3@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'candidat3',
            'lastname' => 'externatic',
            'role' => 'ROLE_CANDIDAT',
        ],
        [
            'email' => 'candidat4@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'candidat4',
            'lastname' => 'externatic',
            'role' => 'ROLE_CANDIDAT',
        ],
        [
            'email' => 'candidat5@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'candidat5',
            'lastname' => 'externatic',
            'role' => 'ROLE_CANDIDAT',
        ],
        [
            'email' => 'candidat6@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'candidat6',
            'lastname' => 'externatic',
            'role' => 'ROLE_CANDIDAT',
        ],
    ];

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        foreach (self::CONSULTANT_INFOS as $consultantInfo) {
            self::$userConsultantIndex++;
            $user = new User();
            $user->setEmail($consultantInfo['email']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $consultantInfo['pass']
            );
            $user->setPassword($hashedPassword);

            $user->setRoles(array($consultantInfo['role']));

            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setPhone($faker->phoneNumber());
            $user->setIsVerified(true);
            $user->setHasNotifUnread(false);
            $manager->persist($user);
            $this->addReference('userConsultant_' . self::$userConsultantIndex, $user);
        }

        for ($i = 0; $i <= 20; $i++) {
            self::$userCandidatIndex++;
            $user = new User();
            $user->setEmail('candidat' . self::$userCandidatIndex . '@mail.fr');

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'motdepasse'
            );
            $user->setPassword($hashedPassword);

            $user->setRoles((array)'ROLE_CANDIDAT');
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setPhone($faker->phoneNumber());
            $user->setIsVerified(true);
            $user->setHasNotifUnread(true);

            $manager->persist($user);

            $this->addReference('userCandidat_' . self::$userCandidatIndex, $user);
        }

        $manager->flush();
    }
}
