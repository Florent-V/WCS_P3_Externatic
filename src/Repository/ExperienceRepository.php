<?php

namespace App\Repository;

use App\Entity\Curriculum;
use App\Entity\Experience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Experience>
 *
 * @method Experience|null find($id, $lockMode = null, $lockVersion = null)
 * @method Experience|null findOneBy(array $criteria, array $orderBy = null)
 * @method Experience[]    findAll()
 * @method Experience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experience::class);
    }

    public function save(Experience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Experience $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findNextExperience(int $id, Curriculum $curriculum, bool $isFormation): ?Experience
    {
        $experience = $this->findOneBy(
            ['id' => $id]
        );
        $beginning = $experience->getBeginning();
        return $this->createQueryBuilder('e')
            ->andWhere('e.beginning > :beginning')
            ->andWhere('e.curriculum = :curriculum')
            ->andWhere('e.isFormation = :isFormation')
            ->setParameter('beginning', $beginning)
            ->setParameter('curriculum', $curriculum)
            ->setParameter('isFormation', $isFormation)
            ->orderBy('e.beginning', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findPreviousExperience(int $id, Curriculum $curriculum, bool $isFormation): ?Experience
    {
        $experience = $this->findOneBy(
            ['id' => $id]
        );
        $beginning = $experience->getBeginning();

        return $this->createQueryBuilder('e')
            ->andWhere('e.beginning < :beginning')
            ->andWhere('e.curriculum = :curriculum')
            ->andWhere('e.isFormation = :isFormation')
            ->setParameter('beginning', $beginning)
            ->setParameter('curriculum', $curriculum)
            ->setParameter('isFormation', $isFormation)
            ->orderBy('e.beginning', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findFirstExperience(Curriculum $curriculum, bool $isFormation): ?Experience
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.curriculum = :curriculum')
            ->andWhere('e.isFormation = :isFormation')
            ->setParameter('curriculum', $curriculum)
            ->setParameter('isFormation', $isFormation)
            ->orderBy('e.beginning', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLastExperience(Curriculum $curriculum, bool $isFormation): ?Experience
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.curriculum = :curriculum')
            ->andWhere('e.isFormation = :isFormation')
            ->setParameter('curriculum', $curriculum)
            ->setParameter('isFormation', $isFormation)
            ->orderBy('e.beginning', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }



//    /**
//     * @return Experience[] Returns an array of Experience objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Experience
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
