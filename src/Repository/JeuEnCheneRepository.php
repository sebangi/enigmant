<?php

namespace App\Repository;

use App\Entity\JeuEnChene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Orm\QueryBuilder;

/**
 * @method JeuEnChene|null find($id, $lockMode = null, $lockVersion = null)
 * @method JeuEnChene|null findOneBy(array $criteria, array $orderBy = null)
 * @method JeuEnChene[]    findAll()
 * @method JeuEnChene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeuEnCheneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JeuEnChene::class);
    }
       
    /**
      * @return JeuEnChene[]
      */
    public function findAllDisponible() : array
    {
        return $this->findDisponibleQuery()
            ->getQuery()
            ->getResult();
    }

    /**
      * @return JeuEnChene[]
      */
    public function findDerniersDisponible() : array
    {
        return $this->findDisponibleQuery()
            ->setMaxResults(4)    
            ->getQuery()
            ->getResult();
    }

    /**
      * @return QueryBuilder
      */
    private function findDisponibleQuery() : QueryBuilder
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.disponible = false');
    }
    
    // /**
    //  * @return JeuEnChene[] Returns an array of JeuEnChene objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JeuEnChene
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
