<?php

namespace App\Repository\General;

use App\Entity\General\ObtentionNiveau;
use App\Entity\General\ObtentionNiveauRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Orm\QueryBuilder;
use Doctrine\Orm\Query;

/**
 * @method ObtentionNiveau|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObtentionNiveau|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObtentionNiveau[]    findAll()
 * @method ObtentionNiveau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObtentionNiveauRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, ObtentionNiveau::class);
    }

    /**
     * @return QueryBuilder
     */
    private function findAllAvecJointureQuery(): QueryBuilder {
        return $this->createQueryBuilder('o')
                        ->select('o', 'niv', 'user', 'theme')
                        ->leftJoin('o.niveau', 'niv')
                        ->leftJoin('o.user', 'user')
                        ->leftJoin('niv.theme', 'theme')
                        ->orderBy('user.username', 'ASC')
                        ->addorderBy('theme.num', 'ASC')
                        ->addorderBy('niv.num', 'DESC')
        ;
    }

    /**
     * @return ObtentionNiveau[]
     */
    public function findAllAvecJointure(ObtentionNiveauRecherche $recherche): array {
        $query = $this->findAllAvecJointureQuery();

        if ($recherche->getUser()) {
            $query = $query
                    ->andwhere('o.user = :user')
                    ->setParameter('user', $recherche->getUser()->getId());
        }

        return $query->getQuery()
                        ->getResult();
    }

    /**
     * 
     * @param type $id_user
     * @param type $id_theme
     * @return ObtentionNiveau|null
     */
    public function getPlusHautGradesTheme($id_user, $id_theme): array  {
        return $this->createQueryBuilder('o')
                        ->select('o', 'niv', 'the')
                        ->Join('o.niveau', 'niv')
                        ->Join('niv.theme', 'the')
                        ->Join('o.user', 'user')
                        ->Where('user.id = :id_u')
                        ->AndWhere('the.id = :id_t')
                        ->orderBy('niv.num', 'DESC')
                        ->setParameter('id_u', $id_user)
                        ->setParameter('id_t', $id_theme)
                        ->getQuery()
                        ->getResult()
        ;
    }

    /**
     * @return ObtentionNiveau[]
     */
    public function getNouveauxGradesTheme($id_user, $nom_theme): array {
        return $this->createQueryBuilder('o')
                        ->select('o', 'niv', 'user', 'the')
                        ->leftJoin('o.niveau', 'niv')
                        ->leftJoin('niv.theme', 'the')
                        ->leftJoin('o.user', 'user')
                        ->Where('user.id = :id_u')
                        ->andWhere('the.nom = :id_t')
                        ->andWhere('o.vu = false')
                        ->orderBy('niv.num', 'ASC')
                        ->setParameter('id_u', $id_user)
                        ->setParameter('id_t', $nom_theme)
                        ->getQuery()
                        ->getResult()
        ;
    }

    /**
     * @return ObtentionNiveau[]
     */
    public function getNouveauxGrades($id_user): array {
        return $this->createQueryBuilder('o')
                        ->select('o', 'niv', 'user', 'the')
                        ->leftJoin('o.niveau', 'niv')
                        ->leftJoin('niv.theme', 'the')
                        ->leftJoin('o.user', 'user')
                        ->Where('user.id = :id_u')
                        ->andWhere('o.vu = false')
                        ->addOrderBy('niv.num', 'ASC')
                        ->setParameter('id_u', $id_user)
                        ->getQuery()
                        ->getResult()
        ;
    }

    // /**
    //  * @return ObtentionNiveau[] Returns an array of ObtentionNiveau objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('o')
      ->andWhere('o.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('o.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?ObtentionNiveau
      {
      return $this->createQueryBuilder('o')
      ->andWhere('o.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
