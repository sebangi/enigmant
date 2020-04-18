<?php

namespace App\Repository\Chene;

use App\Entity\Chene\Babiole;
use App\Entity\Chene\BabioleRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Orm\QueryBuilder;
use Doctrine\Orm\Query;

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
    
    /**
      * @return Query
      */
    public function findAllQuery( BabioleRecherche $recherche ) : Query
    {
        $query = $this->createQueryBuilder('b');
        
        if ( $recherche->getMaxValeur() )
        {
            $query = $query
                ->andwhere( 'b.valeur <= :maxValeur' )
                ->setParameter( 'maxValeur', $recherche->getMaxValeur() );
        }
            
        return $query->getQuery();
    }
   
    
    /**
      * @return Babiole[]
      */
    public function findAllByType() : array
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'typ')
            ->leftJoin('b.typeBabiole', 'typ')
            ->orderBy('typ.num', 'ASC')
            ->getQuery()
            ->getResult()
        ;
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
