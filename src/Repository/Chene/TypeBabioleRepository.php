<?php

namespace App\Repository\Chene;

use App\Entity\Chene\TypeBabiole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeBabiole|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeBabiole|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeBabiole[]    findAll()
 * @method TypeBabiole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeBabioleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeBabiole::class);
    }
    
    /**
      * @return JeuEnChene[]
      */
    public function findAllByNum() : array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.num', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return TypeBabiole[] Returns an array of TypeBabiole objects
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
    public function findOneBySomeField($value): ?TypeBabiole
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
