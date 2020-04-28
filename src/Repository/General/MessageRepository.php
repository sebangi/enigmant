<?php

namespace App\Repository\General;

use App\Entity\General\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }
    
    /**
     * 
     * @param type $id
     */
    public function getNbMessagesNonLus( $user_id, $est_admin ) 
    {
        $QueryBuilder = $this->createQueryBuilder('m')
            ->select('count(m)')
            ->LeftJoin('m.conversation', 'c')
            ->LeftJoin('c.user', 'u');
        
        if ( $est_admin )
        {
            $QueryBuilder
                ->Where('m.vuGourou = false');
        }
        else
        {
            $QueryBuilder
                ->Where('u.id = :id')
                ->andWhere('m.vu = false')
                ->setParameter('id', $user_id);
        }
    
        return $QueryBuilder
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
