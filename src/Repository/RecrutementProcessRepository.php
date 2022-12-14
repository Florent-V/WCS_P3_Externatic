<?php

namespace App\Repository;

use App\Entity\RecrutementProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecrutementProcess>
 *
 * @method RecrutementProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecrutementProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecrutementProcess[]    findAll()
 * @method RecrutementProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecrutementProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecrutementProcess::class);
    }

    public function save(RecrutementProcess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RecrutementProcess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RecrutementProcess[] Returns an array of RecrutementProcess objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RecrutementProcess
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
