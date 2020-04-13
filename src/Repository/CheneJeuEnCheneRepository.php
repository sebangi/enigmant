<?php

namespace App\Repository;

use App\Entity\CheneJeuEnChene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CheneJeuEnChene|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheneJeuEnChene|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheneJeuEnChene[]    findAll()
 * @method CheneJeuEnChene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheneJeuEnCheneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheneJeuEnChene::class);
    }

    // /**
    //  * @return CheneJeuEnChene[] Returns an array of CheneJeuEnChene objects
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
    public function findOneBySomeField($value): ?CheneJeuEnChene
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
