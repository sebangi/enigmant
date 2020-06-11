<?php

namespace App\Repository\General;

use App\Entity\General\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }    
    
    /**
     * 
     * @param type $id
     */
    public function recupererTout( $id ) 
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'o', 'n', 't', 'c', 'm', 'r', 'c2', 'g')
            ->LeftJoin('u.obtentionNiveaux', 'o')
            ->Join('o.niveau', 'n')
            ->Join('n.theme', 't')
            ->LeftJoin('u.grades', 'g')
            ->LeftJoin('u.reservations', 'r')
            ->LeftJoin('r.conversation', 'c2')
            ->LeftJoin('u.conversations', 'c')
            ->LeftJoin('c.messages', 'm')
            ->Where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * 
     */
    public function findAvecGrades( ) 
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'g')
            ->LeftJoin('u.grades', 'g')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * 
     */
    public function getBadgesManquants( )
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'g', 't')
            ->LeftJoin('u.grades', 'g')
            ->LeftJoin('g.theme', 't')
            ->getQuery()
            ->getResult()
        ;
    }
    
    
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
