<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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

    public function getInbox(User $user): array
    {
        $mappingBuilder = new ResultSetMappingBuilder($this->getEntityManager());
        $mappingBuilder->addRootEntityFromClassMetadata('App\Entity\Message', 'm');
        $querySql = 'select m.*,  md.maxDate
                from (
                select Max(date) as maxDate, Max(id) as maxId,
                       recruitment_process_id
                from message
                group by recruitment_process_id
                ) md
                inner join message m
                on md.recruitment_process_id = m.recruitment_process_id
                        and m.date = md.maxDate and m.id = md.maxId
                where m.recruitment_process_id is not null
                and m.send_to_id = ? or send_by_id = ?
                order by m.date DESC';
        $query = $this->getEntityManager()->createNativeQuery($querySql, $mappingBuilder);
        $query->setParameters([1 => $user, 2 => $user]);
        return $query->getResult();
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
