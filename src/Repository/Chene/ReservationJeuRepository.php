<?php

namespace App\Repository\Chene;

use App\Entity\Chene\ReservationJeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationJeu|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationJeu|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationJeu[]    findAll()
 * @method ReservationJeu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationJeuRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ReservationJeu::class);
    }

    /**
     * @return 
     */
    public function findAllSelonUser($id_user): array {
        return $this->createQueryBuilder('r')
                        ->select('r', 'j', 'conv')
                        ->LeftJoin('r.jeu', 'j')
                        ->LeftJoin('r.conversation', 'conv')
                        ->where('r.user = :id_user')
                        ->setParameter('id_user', $id_user)
                        ->orderBy('r.dateDemande', 'DESC')
                        ->getQuery()
                        ->getResult()
        ;
    }

    // /**
    //  * @return ReservationJeu[] Returns an array of ReservationJeu objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('r')
      ->andWhere('r.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('r.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?ReservationJeu
      {
      return $this->createQueryBuilder('r')
      ->andWhere('r.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
