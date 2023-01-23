<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\SearchProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchProfile>
 *
 * @method SearchProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchProfile[]    findAll()
 * @method SearchProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchProfile::class);
    }

    public function save(SearchProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SearchProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearchProfile(Annonce $annonce, int $workTime): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->andWhere('s.salaryMin IS NULL OR s.salaryMin < :annonceSalaryMin')
            ->andWhere('s.remote IS NULL OR s.remote = :annonceIsRemote')
            ->andWhere('s.companyId IS NULL OR s.companyId = :annonceCompanyId')
            ->andWhere('s.workTime IS NULL OR s.workTime = :workTime')
            ->andWhere(':annonceTechno MEMBER OF s.techno')
            ->setParameter('annonceSalaryMin', $annonce->getSalaryMin())
            ->setParameter('annonceIsRemote', $annonce->isRemote())
            ->setParameter('annonceCompanyId', $annonce->getCompany()->getId())
            ->setParameter('workTime', $workTime)
            ->setParameter('annonceTechno', $annonce->getTechno())
            ->getQuery();

        return $queryBuilder->getResult();
    }

//    /**
//     * @return SearchProfile[] Returns an array of SearchProfile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SearchProfile
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
