<?php

namespace App\Repository;

use App\Entity\AgendaComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AgendaComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgendaComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgendaComment[]    findAll()
 * @method AgendaComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgendaComment::class);
    }

    // /**
    //  * @return AgendaComment[] Returns an array of AgendaComment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AgendaComment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
