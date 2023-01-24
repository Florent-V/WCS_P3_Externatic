<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getInbox(string $sendOrReceived, User $user, string $userRole): Query
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.' . $sendOrReceived . " = :user")
            ->setParameter('user', $user)
            ->andWhere('m.recruitmentProcess IS NOT null')
            ->join('m.recruitmentProcess', "r", 'WITH', 'r.archivedBy' . $userRole . ' = false')
            ->orderBy('m.date', 'DESC')
            ->groupBy('m.recruitmentProcess')
            ->getQuery();
    }

    /*
     * -> Tous les messages reçus par le user
     *
     * -> Where recruitProcess IS NOT null
     * -> Join recruitement process
     *
     * -> Join Annonce si non null
     * -> Join company si non null
     *
     * Groupe by recruitementprocess
     * Ne prendre que le dernier message
     *
     *
     * ********* OU ***************
     *
     * -> Tous les processus de recrutement non archivés ET où il y a des messages
     * -> Le dernier message de chacun de ses processus
     *
     * -> Join Annonce si non null
     * -> Join company si non null
     *
     *
     *
     * ********** ET ****************
     * scroll messagerie instantanée
     *
     */
}
