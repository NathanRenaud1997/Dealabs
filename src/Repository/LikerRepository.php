<?php

namespace App\Repository;

use App\Entity\Liker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Liker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liker[]    findAll()
 * @method Liker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Liker::class);
    }
    public function likeByUser($value)
    {
        return $this->createQueryBuilder('l')
            ->select(("count(l)"))
            ->andWhere('l.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Liker[] Returns an array of Liker objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Liker
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
