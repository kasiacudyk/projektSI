<?php

namespace App\Repository;

use App\Entity\UsersData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsersData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersData[]    findAll()
 * @method UsersData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersData::class);
    }

    // /**
    //  * @return UsersData[] Returns an array of UsersData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsersData
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
