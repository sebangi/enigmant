<?php

namespace App\Repository\Chene;

use App\Entity\Chene\CollectionChene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CollectionChene|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollectionChene|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollectionChene[]    findAll()
 * @method CollectionChene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionCheneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CollectionChene::class);
    }

    // /**
    //  * @return CollectionChene[] Returns an array of CollectionChene objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CollectionChene
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
