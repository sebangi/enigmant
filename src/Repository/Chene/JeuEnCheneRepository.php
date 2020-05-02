<?php

namespace App\Repository\Chene;

use App\Entity\Chene\JeuEnChene;
use App\Entity\Chene\JeuEnCheneRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Orm\QueryBuilder;
use Doctrine\Orm\Query;

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
      * @return Query
      */
    public function findAllConstruitQuery( JeuEnCheneRecherche $recherche ) : Query
    {
        $query = $this->findConstruitQuery();
        
        if ( $recherche->getMaxPrix() )
        {
            $query = $query
                ->andwhere( 'j.prix <= :maxPrix' )
                ->setParameter( 'maxPrix', $recherche->getMaxPrix() );
        }
        
        if ( $recherche->getMinDifficulteRaisonnement() )
        {
            $query = $query
                ->andwhere( 'j.difficulteRaisonnement >= :minDifficulteRaisonnement' )
                ->setParameter( 'minDifficulteRaisonnement', $recherche->getMinDifficulteRaisonnement() );
        }
            
        return $query->getQuery();
    }
    
    /**
      * @return JeuEnChene[]
      */
    public function findAllBOrderByCollection() : array
    {
        return $this->createQueryBuilder('j')
            ->select('j', 'col')
            ->leftJoin('j.collectionChene', 'col')
            ->orderBy('col.num', 'ASC')
            ->addOrderBy('j.num', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /**
      * @return JeuEnChene[]
      */
    public function findDerniersDisponible() : array
    {
        return $this->findConstruitQuery()
            ->andWhere('j.disponible = true')
            ->setMaxResults(4)    
            ->getQuery()
            ->getResult();
    }

    /**
      * @return QueryBuilder
      */
    private function findConstruitQuery() : QueryBuilder
    {
        return $this->createQueryBuilder('j')
            ->select('j', 'babs')
            ->leftJoin('j.babioles', 'babs')
            ->andWhere('j.construit = true')
            ->leftJoin('j.collectionChene', 'col')
            ->orderBy('col.num', 'ASC')
            ->addOrderBy('j.num', 'ASC');
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
