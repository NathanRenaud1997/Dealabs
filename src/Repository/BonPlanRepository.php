<?php

namespace App\Repository;

use App\Entity\BonPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BonPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method BonPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method BonPlan[]    findAll()
 * @method BonPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BonPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BonPlan::class);
    }

    public function findALaUne()
    {
        $now = new \DateTime('now');
        $now->format("Y-m-d");
        $date = new \DateTime("now");
        $date->format("Y-m-d");
        $date = $date->modify("-7 day");
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateCreation BETWEEN  :date AND :now')
            ->setParameter(':date', $date)
            ->setParameter(':now', $now)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findByUserId($userId)
    {
        return $this->createQueryBuilder("b")
            ->andWhere("b.userCrea = :userId")
            ->setParameter(':userId', $userId)
            ->getQuery()
            ->getResult();
    }
    public function findAlertByUserId($userId)
    {
        return $this->createQueryBuilder("b")
            ->join('b.alertesUsers',"a")
            ->andWhere("a.id = :userId")
            ->setParameter(':userId', $userId)
            ->getQuery()
            ->getResult();
    }
    public function countByUserId($userId)
    {
        return $this->createQueryBuilder("b")
            ->select("count(b.id)")
            ->andWhere('b.userCrea = :userId')
            ->setParameter(':userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function selectHottestBonPlanByUserId($userId)
    {
        return $this->createQueryBuilder("b")
            ->select("max(b.note)")
            ->andWhere("b.userCrea = :userId")
            ->setParameter(":userId", $userId)
            ->getQuery()
            ->getResult();
    }

    public function selectAverageRateByUserId($userId)
    {
        $now = new \DateTime('now');
        $now->format("Y-m-d");
        $date = new \DateTime("now");
        $date->format("Y-m-d");
        $date = $date->modify("-1 year");

        return $this->createQueryBuilder("b")
            ->select("avg(b.note)")
            ->andWhere("b.userCrea = :userId AND b.dateCreation BETWEEN :date AND :now")
            ->setParameter(":userId", $userId)
            ->setParameter(":date", $date)
            ->setParameter(":now", $now)
            ->getQuery()
            ->getResult();
    }

    public function countHotByUserId($userId)
    {
        return $this->createQueryBuilder("b")
            ->select("count(b.note)")
            ->andWhere("b.userCrea = :userId AND b.note > 100")
            ->setParameter(":userId", $userId)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return BonPlan[] Returns an array of BonPlan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BonPlan
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findHot()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.note >=100')
            ->getQuery()
            ->getResult();
    }

    public function findBySearch(string $valueSearch)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.description LIKE :value OR c.title LIKE :value')
            ->setParameter(":value",'%'.$valueSearch."%")
            ->getQuery()
            ->getResult();
    }

}
