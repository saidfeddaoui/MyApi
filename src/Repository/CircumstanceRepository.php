<?php

namespace App\Repository;

use App\Entity\Circumstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Circumstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Circumstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Circumstance[]    findAll()
 * @method Circumstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CircumstanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Circumstance::class);
    }

//    /**
//     * @return Circumstance[] Returns an array of Circumstance objects
//     */
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
    public function findOneBySomeField($value): ?Circumstance
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
