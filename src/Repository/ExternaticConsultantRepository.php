<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\ExternaticConsultant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function findConsultant(string $search = ''): Query
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u', 'WITH', 'u.isActive = true');

        if ($search) {
            $qb->where($qb->expr()->orX(
                $qb->expr()->like('u.firstname', ':search'),
                $qb->expr()->like('u.lastName', ':search'),
                $qb->expr()->like('u.email', ':search')
            ))
                ->setParameter('search', '%' . $search . '%');
        }
        return $qb->getQuery();
    }



/*    public function getRecruitProcessByAnnonce(Annonce $annonce): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.consultant = :consultantId')
            ->setParameter('consultantId', $consultant);
        if ($plage === "thisWeek") {
            $queryBuilder->andWhere("a.date >= :date1")
                ->setParameter('date1', $today)
                ->andWhere("a.date <= :date2")
                ->setParameter("date2", $sundayMidnight);
        } elseif ($plage === "thisMonth") {
            $queryBuilder->andWhere("a.date > :date1")
                ->setParameter('date1', $sundayMidnight)
                ->andWhere("a.date <= :date2")
                ->setParameter("date2", $nextMonth);
        }
        $queryBuilder->orderBy('a.date', 'ASC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }*/

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
