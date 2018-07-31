<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    /**
     * UserRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }
    /**
     * Load all users
     * @return User[]
     */
    public function getUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u INSTANCE OF :discriminator')
            ->setParameter('discriminator', 'user')
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * Load user by username
     * @param $username
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByCanonicalUsername($username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.usernameCanonical = :usernameCanonical')
            ->andWhere('u INSTANCE OF :discriminator')
            ->setParameters(['usernameCanonical' => $username, 'discriminator' => 'user'])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
//    /**
//     * @return User[] Returns an array of User objects
//     */
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
