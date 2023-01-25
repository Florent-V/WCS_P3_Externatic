<?php

namespace App\Repository;

use App\Entity\RecruitmentProcess;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<RecruitmentProcess>
 *
 * @method RecruitmentProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecruitmentProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecruitmentProcess[]    findAll()
 * @method RecruitmentProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecruitmentProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly Security $security)
    {
        parent::__construct($registry, RecruitmentProcess::class);
    }

    public function save(RecruitmentProcess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RecruitmentProcess $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function changeStatus(RecruitmentProcess $recruitmentProcess): ?bool
    {
        if ($this->getRelationToRecruitmentProcess($recruitmentProcess) == "Candidat") {
            $recruitmentProcess->setReadByCandidat(!($recruitmentProcess->isReadByCandidat()));
            $this->save($recruitmentProcess, true);
            return $recruitmentProcess->isReadByCandidat();
        } elseif ($this->getRelationToRecruitmentProcess($recruitmentProcess) == "Consultant") {
            $recruitmentProcess->setReadByConsultant(!($recruitmentProcess->isReadByconsultant()));
            $this->save($recruitmentProcess, true);
            return $recruitmentProcess->isReadByConsultant();
        }
        return null;
    }

    public function changeArchived(RecruitmentProcess $recruitmentProcess): ?bool
    {
        if ($this->getRelationToRecruitmentProcess($recruitmentProcess) == "Candidat") {
            $recruitmentProcess->setArchivedByCandidat(!($recruitmentProcess->isArchivedByCandidat()));
            $this->save($recruitmentProcess, true);
            return $recruitmentProcess->isReadByCandidat();
        } elseif ($this->getRelationToRecruitmentProcess($recruitmentProcess) == "Consultant") {
            $recruitmentProcess->setArchivedByConsultant(!($recruitmentProcess->isArchivedByconsultant()));
            $this->save($recruitmentProcess, true);
            return $recruitmentProcess->isArchivedByConsultant();
        }
        return null;
    }

    public function changeRate(RecruitmentProcess $recruitmentProcess, int $rate): ?int
    {
        if ($this->getRelationToRecruitmentProcess($recruitmentProcess) == "Consultant") {
            $recruitmentProcess->setRate($rate);
            $this->save($recruitmentProcess, true);
            return $recruitmentProcess->getRate();
        }
        return null;
    }

    public function getRelationToRecruitmentProcess(RecruitmentProcess $recruitmentProcess): ?string
    {
        /** @var ?User $user */
        $user = $this->security->getUser();

        if ($user == $recruitmentProcess->getCandidat()->getUser()) {
            return "Candidat";
        } elseif ($user == $recruitmentProcess->getExternaticConsultant()->getUser()) {
            return "Consultant";
        }
        return null;
    }

//    /**
//     * @return RecruitmentProcess[] Returns an array of RecruitmentProcess objects
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

//    public function findOneBySomeField($value): ?RecruitmentProcess
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
