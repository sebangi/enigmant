<?php

namespace App\Repository\General;

use App\Entity\General\Conversation;
use App\Entity\General\ConversationRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Orm\QueryBuilder;
use Doctrine\Orm\Query;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * 
     * @return QueryBuilder
     */
    public function getByDateQuery(): QueryBuilder {
        return $this->createQueryBuilder('c')
                        ->select('c', 'mess', 'resa', 'jeu', 'j')
                        ->Join('c.user', 'user')
                        ->LeftJoin('c.messages', 'mess')
                        ->LeftJoin('c.lienReservation', 'resa')
                        ->LeftJoin('resa.jeu', 'j')
                        ->LeftJoin('c.lienJeuEnChene', 'jeu')
        ;
    }

    /**
     * 
     * @param type $user_id
     * @param ConversationRecherche|null $recherche
     * @return array
     */
    public function getByDate($user_id, $recherche): array {
        $query = $this->getByDateQuery();

        if ($user_id) {
            $query = $query
                    ->Where('user.id = :id')
                    ->setParameter('id', $user_id)
                    ->addorderBy('mess.vu', 'ASC')
                    ->addOrderBy('mess.date', 'DESC');
        } else {
            $query = $query
                    ->orderBy('mess.vuGourou', 'ASC')
                    ->addOrderBy('mess.date', 'DESC');
        }

        if ($recherche) {
            if ($recherche->getUser()) {
                $query = $query
                        ->andwhere('user.id = :user')
                        ->setParameter('user', $recherche->getUser()->getId());
            }
            
            if ($recherche->getTheme()) {
                $query = $query
                        ->andwhere('c.theme = :theme')
                        ->setParameter('theme', $recherche->getTheme()->getId());
            }
            
            if ($recherche->getQuestion()) {
                $query = $query
                        ->andwhere('c.question = true');
            }
        }

        return $query
                        ->getQuery()
                        ->getResult()
        ;
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('c')
      ->andWhere('c.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('c.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Conversation
      {
      return $this->createQueryBuilder('c')
      ->andWhere('c.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
