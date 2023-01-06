<?php

namespace App\Repository;

use App\Entity\Appointement;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointement>
 *
 * @method Appointement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointement[]    findAll()
 * @method Appointement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointement::class);
    }

    public function save(Appointement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Appointement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAppoitmentList(int $consultantId, string $plage): array
    {
        $nbOfDaysUntilSunday = [0, 6, 5, 4, 3, 2, 1];

        $today = new DateTimeImmutable();

        $intervalUntilSunday = new DateInterval('P' .
            $nbOfDaysUntilSunday[$today->format('w')] . "D");
        $sundayMidnight = $today->add($intervalUntilSunday)->setTime(23, 59);

        $nextMonth = $today->add(new DateInterval('P1M'))->setTime(23, 59);

        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.consultant = :consultantId')
            ->setParameter('consultantId', $consultantId);
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
    }

//    /**
//     * @return Appointement[] Returns an array of Appointement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Appointement
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
