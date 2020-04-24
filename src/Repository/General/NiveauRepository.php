<?php

namespace App\Repository\General;

use App\Entity\General\Niveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Orm\QueryBuilder;
use Doctrine\Orm\Query;


/**
 * @method Niveau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Niveau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Niveau[]    findAll()
 * @method Niveau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Niveau::class);
    }
    
    /**
      * @return QueryBuilder
      */
    public function findAllByThemeQueryBuilder() : QueryBuilder
    {
        return $this->createQueryBuilder('n')
            ->select('n', 'the')
            ->leftJoin('n.theme', 'the')
            ->orderBy('the.num', 'ASC')
            ->addOrderBy('n.num', 'ASC')
        ;
    }
    
    /**
      * @return Niveau[]
      */
    public function findAllByTheme() : array
    {
        return $this->findAllByThemeQueryBuilder()                
            ->getQuery()
            ->getResult()
        ;
    }

    public function getGrades( $id, $theme ) 
    {
        return $this->createQueryBuilder('niveau')
            ->select('niveau', 'theme', 'obtention')
            ->Join('niveau.theme', 'theme')
            ->LeftJoin('niveau.obtentionNiveaux', 'obtention')
            ->LeftJoin('obtention.user', 'user')
            ->Where('user.id = :id or obtention.id is null')
            ->AndWhere('theme.nom = :theme')
            ->orderBy('theme.num', 'ASC')
            ->addOrderBy('niveau.num', 'ASC')
            ->setParameter('id', $id)
            ->setParameter('theme', $theme)
            ->getQuery()
            ->getScalarResult()
        ;
    } 
    
    public function getGradesDunTheme( $theme ) 
    {
        return $this->createQueryBuilder('niveau')
            ->select('niveau', 'theme')
            ->Join('niveau.theme', 'theme')
            ->Where('theme.nom = :theme')
            ->OrderBy('niveau.num', 'ASC')
            ->setParameter('theme', $theme)
            ->getQuery()
            ->getScalarResult()
        ;
    } 
    
    // /**
    //  * @return Niveau[] Returns an array of Niveau objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Niveau
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
