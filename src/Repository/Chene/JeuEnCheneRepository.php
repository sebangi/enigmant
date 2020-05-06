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
      * @return JeuEnChene[]
      */
    public function findAllConstruit( JeuEnCheneRecherche $recherche ) : array
    {
        $query = $this->findConstruitQuery();
        
        if ( $recherche->getDisponible() )
        {
            $query = $query
                ->andwhere( 'j.disponible = :disponible' )
                ->setParameter( 'disponible', $recherche->getDisponible() );
        }
        
        if ( $recherche->getCollection() )
        {
            $query = $query
                ->andwhere( 'col.id = :collection' )
                ->setParameter( 'collection', $recherche->getCollection()->getId() );
        }
        
        if ( $recherche->getMaxPrix() )
        {
            $query = $query
                ->andwhere( 'j.prix <= :maxPrix' )
                ->setParameter( 'maxPrix', $recherche->getMaxPrix() );
        }
        
        if ( $recherche->getMinPrix() )
        {
            $query = $query
                ->andwhere( 'j.prix >= :maxPrix' )
                ->setParameter( 'maxPrix', $recherche->getMinPrix() );
        }
        
        if ( $recherche->getMinDifficulteRaisonnement() )
        {
            $query = $query
                ->andwhere( 'j.difficulteRaisonnement >= :minDifficulteRaisonnement' )
                ->setParameter( 'minDifficulteRaisonnement', $recherche->getMinDifficulteRaisonnement() );
        }
        
        if ( $recherche->getMaxDifficulteRaisonnement() )
        {
            $query = $query
                ->andwhere( 'j.difficulteRaisonnement <= :maxDifficulteRaisonnement' )
                ->setParameter( 'maxDifficulteRaisonnement', $recherche->getMaxDifficulteRaisonnement() );
        }
        
        
        if ( $recherche->getMinDifficulteObservation() )
        {
            $query = $query
                ->andwhere( 'j.difficulteObservation >= :minDifficulteObservation' )
                ->setParameter( 'minDifficulteObservation', $recherche->getMinDifficulteObservation() );
        }
        
        if ( $recherche->getMaxDifficulteObservation() )
        {
            $query = $query
                ->andwhere( 'j.difficulteObservation <= :maxDifficulteObservation' )
                ->setParameter( 'maxDifficulteObservation', $recherche->getMaxDifficulteObservation() );
        }
            
        return $query->getQuery()
            ->getResult();
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
