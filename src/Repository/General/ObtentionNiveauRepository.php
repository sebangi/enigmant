<?php

namespace App\Repository\General;

use App\Entity\General\ObtentionNiveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ObtentionNiveau|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObtentionNiveau|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObtentionNiveau[]    findAll()
 * @method ObtentionNiveau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObtentionNiveauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObtentionNiveau::class);
    }
    
    /**
      * @return ObtentionNiveau[]
      */
    public function findAllAvecJointure() : array
    {
        return $this->createQueryBuilder('o')
            ->select('o', 'niv', 'user')
            ->leftJoin('o.niveau', 'niv')
            ->leftJoin('o.user', 'user')
            ->orderBy('user.username', 'ASC')
            ->addorderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return ObtentionNiveau[] Returns an array of ObtentionNiveau objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ObtentionNiveau
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
