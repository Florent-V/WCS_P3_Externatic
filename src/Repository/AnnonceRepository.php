<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    private const FULL_TIME = 35;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function save(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function annonceFinder(array $searchInformations): array
    {

        $searchInformations['searchQuery'] = empty($searchInformations['searchQuery']) ? "*"
            : $searchInformations['searchQuery'];
        //dd($searchInformations);
        //Annonce title
        $queryBuilder = $this->createQueryBuilder('a')
            ->distinct()
            ->andWhere('a.title LIKE :searchQuery')
            ->setParameter('searchQuery', '%' . $searchInformations['searchQuery'] . '%');

        //Minimum Salary
        if (!empty($searchInformations['salaryMin'])) {
            $queryBuilder->andWhere('a.salary > :salaryMin')
                ->setParameter('salaryMin', $searchInformations['salaryMin']);
        }

        //Contract types
        if (!empty($searchInformations['contractType'])) {
            foreach ($searchInformations['contractType'] as $key => $contractType) {
                if ($key == 0) {
                    $queryBuilder->andWhere('a.contractType=:contractType')
                        ->setParameter('contractType', $contractType);
                } else {
                    $queryBuilder->orWhere('a.contractType=:contractType')
                        ->setParameter('contractType', $contractType);
                }
            }
        }

        //remote
        if (isset($searchInformations['remote']) && $searchInformations['remote'] != "") {
            $queryBuilder->andWhere('a.remote=:remote')
                ->setParameter('remote', $searchInformations['remote']);
        }

        //workTime
        if (isset($searchInformations['workTime']) && $searchInformations['workTime'] != "") {
            $worktimeOperator = $searchInformations['workTime'] ? ">=" : "<";
            $queryBuilder->andWhere("a.workTime $worktimeOperator " . self::FULL_TIME);
        }

        //date
        if (!empty($searchInformations['period'])) {
            $searchPeriod = new \DateTime();
            $searchPeriod->sub(new \DateInterval("P" . $searchInformations['period'] . "D"));
            $queryBuilder->andWhere("a.createdAt < :searchPeriod")
                ->setParameter("searchPeriod", $searchPeriod);
        }

        $queryBuilder->orderBy('a.createdAt', 'ASC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Annonce[] Returns an array of Annonce objects
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

//    public function findOneBySomeField($value): ?Annonce
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
