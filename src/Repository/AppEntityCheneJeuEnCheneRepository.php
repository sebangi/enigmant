<?php

namespace App\Repository;

use App\Entity\AppEntityCheneJeuEnChene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppEntityCheneJeuEnChene|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppEntityCheneJeuEnChene|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppEntityCheneJeuEnChene[]    findAll()
 * @method AppEntityCheneJeuEnChene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppEntityCheneJeuEnCheneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppEntityCheneJeuEnChene::class);
    }

    // /**
    //  * @return AppEntityCheneJeuEnChene[] Returns an array of AppEntityCheneJeuEnChene objects
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
    public function findOneBySomeField($value): ?AppEntityCheneJeuEnChene
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
