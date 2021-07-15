<?php

namespace App\Repository;

use App\Entity\CodePromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodePromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodePromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodePromo[]    findAll()
 * @method CodePromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodePromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodePromo::class);
    }

    /**
     * @return CodePromo[] Returns an array of CodePromo objects
     */
    public function findAlertByUserId($userId)
    {
        return $this->createQueryBuilder("c")
            ->join('c.alertesUsers',"a")
            ->andWhere("a.id = :userId")
            ->setParameter(':userId', $userId)
            ->getQuery()
            ->getResult();
    }
    public function findHot()
    {

        return $this->createQueryBuilder('c')
            ->andWhere('c.note >=100')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CodePromo[] Returns an array of CodePromo objects
     */

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
            ->getQuery()
            ->getResult();
    }

    public function findByUserId($userId)
    {
        return $this->createQueryBuilder("c")
            ->andWhere("c.userCrea = :userId")
            ->setParameter(':userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function countByUserId($userId)
    {
        return $this->createQueryBuilder("c")
            ->select("count(c.id)")
            ->andWhere('c.userCrea = :userId')
            ->setParameter(':userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function selectHottestCodePromoByUserId($userId)
    {
        return $this->createQueryBuilder("c")
            ->select("max(c.note)")
            ->andWhere("c.userCrea = :userId")
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

        return $this->createQueryBuilder("c")
            ->select("avg(c.note)")
            ->andWhere("c.userCrea = :userId AND c.dateCreation BETWEEN :date AND :now")
            ->setParameter(":userId", $userId)
            ->setParameter(":date", $date)
            ->setParameter(":now", $now)
            ->getQuery()
            ->getResult();
    }

    public function countHotByUserId($userId)
    {
        return $this->createQueryBuilder("c")
            ->select("count(c.note)")
            ->andWhere("c.userCrea = :userId AND c.note > 100")
            ->setParameter(":userId", $userId)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?CodePromo
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findBySearch(string $valueSearch)
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.description LIKE :value OR c.title LIKE :value')
        ->setParameter(":value",'%'.$valueSearch."%")
        ->getQuery()
        ->getResult();
    }
}
