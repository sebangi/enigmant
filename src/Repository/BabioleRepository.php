<?php

namespace App\Repository;

use App\Entity\Babiole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Babiole|null find($id, $lockMode = null, $lockVersion = null)
 * @method Babiole|null findOneBy(array $criteria, array $orderBy = null)
 * @method Babiole[]    findAll()
 * @method Babiole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BabioleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Babiole::class);
    }

    // /**
    //  * @return Babiole[] Returns an array of Babiole objects
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
    public function findOneBySomeField($value): ?Babiole
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
