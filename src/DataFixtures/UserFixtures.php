<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public static int $userAdminIndex = 0;
    public static int $userConsultantIndex = 0;
    public static int $userCandidatIndex = 0;

    public const ADMIN_INFOS = [
        [
            'email' => 'admin@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'admin',
            'lastname' => 'externatic',
            'role' => 'ROLE_ADMIN'
        ],
    ];

    public const CONSULTANT_INFOS = [
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
        ],
        [
            'email' => 'consultant3@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'consultant3',
            'lastname' => 'externatic',
            'role' => 'ROLE_CONSULTANT'
        ],

    ];

    public const CANDIDAT_INFOS = [

        [
            'email' => 'candidat1@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'candidat1',
            'lastname' => 'externatic',
            'role' => 'ROLE_CANDIDAT',
        ],
        [
            'email' => 'candidat2@mail.fr',
            'pass' => 'motdepasse',
            'firstname' => 'candidat2',
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
        foreach (self::ADMIN_INFOS as $adminInfo) {
            self::$userAdminIndex++;
            $user = new User();
            $user->setEmail($adminInfo['email']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $adminInfo['pass']
            );
            $user->setPassword($hashedPassword);

            $user->setRoles(array($adminInfo['role']));

            $user->setFirstname($adminInfo['firstname']);
            $user->setLastname($adminInfo['lastname']);
            $manager->persist($user);
            $this->addReference('userAdmin_' . self::$userAdminIndex, $user);
        }

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

            $user->setFirstname($consultantInfo['firstname']);
            $user->setLastname($consultantInfo['lastname']);
            $manager->persist($user);
            $this->addReference('userConsultant_' . self::$userConsultantIndex, $user);
        }

        foreach (self::CANDIDAT_INFOS as $candidatInfo) {
            self::$userCandidatIndex++;
            $user = new User();
            $user->setEmail($candidatInfo['email']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $candidatInfo['pass']
            );
            $user->setPassword($hashedPassword);

            $user->setRoles(array($candidatInfo['role']));

            $user->setFirstname($candidatInfo['firstname']);
            $user->setLastname($candidatInfo['lastname']);
            $manager->persist($user);
            $this->addReference('userCandidat_' . self::$userCandidatIndex, $user);
        }

        $manager->flush();
    }
}
