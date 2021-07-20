<?php

namespace App\Repository;

use App\Entity\ReplyEventUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReplyEventUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReplyEventUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReplyEventUser[]    findAll()
 * @method ReplyEventUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReplyEventUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReplyEventUser::class);
    }

    // /**
    //  * @return ReplyEventUser[] Returns an array of ReplyEventUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReplyEventUser
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
