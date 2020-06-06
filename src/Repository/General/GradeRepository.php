<?php

namespace App\Repository\General;

use App\Entity\General\Grade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Grade|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grade|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grade[]    findAll()
 * @method Grade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    /**
     * 
     * @param type $id_user
     * @param type $theme
     * @return Grade|null
     */
    public function getGrades( $id_user, $theme ) : ?array//?Grade
    {
        return $this->createQueryBuilder('g')
            ->select('g.num')
            ->Join('g.theme', 'the')
            ->Join('g.user', 'user')
            ->Where('user.id = :id_user')
            ->AndWhere('the.nom = :theme')
            ->setParameter('id_user', $id_user)
            ->setParameter('theme', $theme)
            ->getQuery()
            ->getFirstResult()
        ;
    }    
    
    // /**
    //  * @return Grade[] Returns an array of Grade objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Grade
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
