<?php

namespace App\Repository;

use App\Entity\Candidat;
use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidat>
 *
 * @method Candidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidat[]    findAll()
 * @method Candidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidat::class);
    }

    public function save(Candidat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Candidat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findActiveCandidat(string $search = ''): Query
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u', 'WITH', 'u.isActive = true');

        if ($search) {
            $qb->where($qb->expr()->orX(
                $qb->expr()->like('u.firstname', ':search'),
                $qb->expr()->like('u.lastName', ':search'),
                $qb->expr()->like('u.email', ':search'),
                $qb->expr()->like('c.city', ':search')
            ))
                ->setParameter('search', '%' . $search . '%');
        }
        return $qb->getQuery();
    }

    public function findCandidatByCriteria(string $research = ''): Query
    {
        $keywords = explode(" ", $research);

        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u', 'WITH', 'u.isActive = true')
            ->innerJoin('c.curriculum', 'cu')
            ->innerJoin('cu.skills', 's')
            ->innerJoin('s.hardSkill', 'hs')
            ->innerJoin('s.softSkill', 'ss')
            ->innerJoin('s.languages', 'l')
            ->innerJoin('cu.experience', 'exp')
            ->innerJoin('cu.certifications', 'cert');

        foreach ($keywords as $key => $search) {
                $qb->orWhere($qb->expr()->orX(
                    $qb->expr()->like('u.firstname', ':search' . $key),
                    $qb->expr()->like('u.lastName', ':search' . $key),
                    $qb->expr()->like('u.email', ':search' . $key),
                    $qb->expr()->like('c.city', ':search' . $key),
                    $qb->expr()->like('c.description', ':search' . $key),
                    $qb->expr()->like('cert.title', ':search' . $key),
                    $qb->expr()->like('exp.title', ':search' . $key),
                    $qb->expr()->like('exp.organism', ':search' . $key),
                    $qb->expr()->like('exp.diploma', ':search' . $key),
                    $qb->expr()->like('hs.name', ':search' . $key),
                    $qb->expr()->like('ss.name', ':search' . $key),
                    $qb->expr()->like('l.language', ':search' . $key),
                ))
                    ->setParameter('search' . $key, '%' . $search . '%');
        }

        return $qb->getQuery();
    }

    public function findByFavCompany(Company $company): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT c FROM App\Entity\Candidat c WHERE :company MEMBER OF c.favoriteCompanies'
        )
            ->setParameter('company', $company);

        return $query->execute();
    }

    public function countCandidat(): array
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery();

        return $queryBuilder->getResult();
    }

//    /**
//     * @return Candidat[] Returns an array of Candidat objects
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

//    public function findOneBySomeField($value): ?Candidat
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
