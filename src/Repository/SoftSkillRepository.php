<?php

namespace App\Repository;

use App\Entity\SoftSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SoftSkill>
 *
 * @method SoftSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoftSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoftSkill[]    findAll()
 * @method SoftSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoftSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoftSkill::class);
    }

    public function save(SoftSkill $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SoftSkill $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
