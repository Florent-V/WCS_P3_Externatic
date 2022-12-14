<?php

namespace App\Repository;

use App\Entity\CandidatHasTechno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CandidatHasTechno>
 *
 * @method CandidatHasTechno|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidatHasTechno|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidatHasTechno[]    findAll()
 * @method CandidatHasTechno[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatHasTechnoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidatHasTechno::class);
    }

    public function save(CandidatHasTechno $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CandidatHasTechno $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CandidatHasTechno[] Returns an array of CandidatHasTechno objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CandidatHasTechno
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
