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
    
    /**
     * @return 
     */
    public function findAvecConversationEtJeu($id_resa) {
        return $this->createQueryBuilder('r')
                        ->select('r', 'j', 'conv')
                        ->Join('r.jeu', 'j')
                        ->LeftJoin('r.conversation', 'conv')
                        ->where('r.id = :id_resa')
                        ->setParameter('id_resa', $id_resa)
                        ->getQuery()
                        ->getResult()
        ;
    }

    /**
     * @return 
     */
    public function findAllReservationsAvecAvis($id_jeu): array {
        return $this->createQueryBuilder('r')
                        ->select('r', 'u')
                        ->Join('r.jeu', 'j')
                        ->Join('r.user', 'u')
                        ->where('j.id = :id_jeu')
                        ->andWhere('r.avisPublic is not null')
                        ->setParameter('id_jeu', $id_jeu)
                        ->orderBy('r.dateDemande', 'DESC')
                        ->getQuery()
                        ->getResult()
        ;
    }
    
    /**
     * @return 
     */
    public function findNoteMoyenne($id_jeu): array {
        return $this->createQueryBuilder('r')
                        ->select("avg(r.note)")
                        ->Join('r.jeu', 'j')
                        ->where('j.id = :id_jeu')
                        ->andWhere('r.note != -1')
                        ->setParameter('id_jeu', $id_jeu)
                        ->getQuery()
                        ->getResult()
        ;
    }
    
    /**
     * @return 
     */
    public function findNbNotes($id_jeu, $note): array {
        return $this->createQueryBuilder('r')
                        ->select("count(r.note)")
                        ->Join('r.jeu', 'j')
                        ->where('j.id = :id_jeu')
                        ->andWhere('r.note = :note')
                        ->groupBy('r.note')
                        ->setParameter('id_jeu', $id_jeu)
                        ->setParameter('note', $note)
                        ->getQuery()
                        ->getResult()
        ;
    }
    
    public function getNbReussi($id_user) : array
    {
        return $this->createQueryBuilder('r')
            ->select('col.num, count(j.id) as count')
            ->Join('r.jeu', 'j')
            ->Join('r.user', 'user')
            ->LeftJoin('j.collectionChene', 'col')
            ->orderBy('col.num', 'ASC')
            ->Where('r.reussi = true')
            ->andWhere('user.id = :id_u')
            ->groupBy('col.num')
            ->setParameter('id_u', $id_user)
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
