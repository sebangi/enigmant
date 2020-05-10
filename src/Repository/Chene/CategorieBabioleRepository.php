<?php

namespace App\Repository\Chene;

use App\Entity\Chene\CategorieBabiole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieBabiole|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieBabiole|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieBabiole[]    findAll()
 * @method CategorieBabiole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieBabioleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieBabiole::class);
    }
    
    /**
      * @return CategorieBabiole[]
      */
    public function findAllByNum() : array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.num', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
      * @return CategorieBabiole[]
      */
    public function findAllWithBabioleDeType( $numType ) : array
    {
        return $this->createQueryBuilder('c')
            ->Join('c.babioles', 'bab')
            ->Join('bab.typeBabiole', 'typ')
            ->where( 'typ.num = :numType' )
            ->setParameter( 'numType', $numType )
            ->orderBy('c.num', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
        
    // /**
    //  * @return CategorieBabiole[] Returns an array of CategorieBabiole objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieBabiole
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
