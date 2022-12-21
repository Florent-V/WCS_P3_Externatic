<?php

namespace App\Repository;

use App\Entity\Annonce;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
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

    public function annonceFinder(mixed $searchInformations): array
    {

        //Annonce title
        $queryBuilder = $this->createQueryBuilder('a')
            ->distinct()
            ->andWhere('a.title LIKE :searchQuery')
            ->setParameter('searchQuery', '%' . $searchInformations['searchQuery'] . '%');

        //Minimum Salary and remote
        $this->getSalaryAndRemoteQuery($queryBuilder, $searchInformations);

        //Contract types
        if (!empty($searchInformations['contractType'])) {
            $queryBuilder->addCriteria(self::getContractQuery($searchInformations['contractType']));
        }


        //workTime
        if (isset($searchInformations['workTime']) && $searchInformations['workTime'] != "") {
            $worktimeOperator = $searchInformations['workTime'] ? ">=" : "<";
            $queryBuilder->andWhere("a.workTime $worktimeOperator " . self::FULL_TIME);
        }

        //date
        if (!empty($searchInformations['period'])) {
            $searchPeriod = new DateTime();
            $searchPeriod->sub(new DateInterval("P" . $searchInformations['period'] . "D"));
            $queryBuilder->andWhere("a.createdAt > :searchPeriod")
                ->setParameter("searchPeriod", $searchPeriod);
        }

        if (!empty($searchInformations['company'])) {
            $queryBuilder->join("a.company", "c")
                ->andWhere("c.id = :company_id")
                ->setParameter("company_id", $searchInformations['company']);
        }

        if (!empty($searchInformations['techno'])) {
            $queryBuilder->join("a.techno", "t");
            $queryBuilder->addCriteria(self::getTechnoQuery($searchInformations['techno']));
        }

        $queryBuilder->orderBy('a.createdAt', 'ASC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public static function getContractQuery(array $contractTypes): Criteria
    {
        $criteria = Criteria::create();
        foreach ($contractTypes as $key => $contractType) {
            if ($key == 0) {
                $criteria->andWhere(Criteria::expr()->eq('contractType', $contractType));
            } else {
                $criteria->orWhere(Criteria::expr()->eq('contractType', $contractType));
            }
        }
        return $criteria;
    }

    public static function getTechnoQuery(array $technos): Criteria
    {
        $criteria = Criteria::create();
        foreach ($technos as $key => $techno) {
            if ($key == 0) {
                $criteria->andWhere(Criteria::expr()->eq('t.id', $techno));
            } else {
                $criteria->orWhere(Criteria::expr()->eq('t.id', $techno));
            }
        }
        return $criteria;
    }

    private function getSalaryAndRemoteQuery(QueryBuilder $queryBuilder, mixed $searchInformations): void
    {
        if (!empty($searchInformations['salaryMin'])) {
            $queryBuilder->andWhere('a.salaryMin > :salaryMin')
                ->setParameter('salaryMin', $searchInformations['salaryMin']);
        }
        if (isset($searchInformations['remote']) && $searchInformations['remote'] != "") {
            $queryBuilder->andWhere('a.remote=:remote')
                ->setParameter('remote', $searchInformations['remote']);
        }
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
