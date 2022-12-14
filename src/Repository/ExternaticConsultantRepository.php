<?php

namespace App\Repository;

use App\Entity\ExternaticConsultant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExternaticConsultant>
 *
 * @method ExternaticConsultant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternaticConsultant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternaticConsultant[]    findAll()
 * @method ExternaticConsultant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternaticConsultantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternaticConsultant::class);
    }

    public function save(ExternaticConsultant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExternaticConsultant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ExternaticConsultant[] Returns an array of ExternaticConsultant objects
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

//    public function findOneBySomeField($value): ?ExternaticConsultant
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
