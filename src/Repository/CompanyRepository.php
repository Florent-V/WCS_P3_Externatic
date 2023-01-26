<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function save(Company $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Company $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countCompany(): array
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findCompany(string $search = ''): Query
    {
        $qb = $this->createQueryBuilder('c')
        ->innerJoin('c.externaticConsultant', 'ec')
        ->innerJoin('ec.user', 'u');

        if ($search) {
            $qb->where($qb->expr()->orX(
                $qb->expr()->like('c.name', ':search'),
                $qb->expr()->like('c.information', ':search'),
                $qb->expr()->like('c.city', ':search'),
                $qb->expr()->like('u.firstname', ':search'),
                $qb->expr()->like('u.lastName', ':search')
            ))
                ->setParameter('search', '%' . $search . '%');
        }
        return $qb->getQuery();
    }
}
