<?php

namespace App\Repository;

use App\Entity\Circonstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Circonstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Circonstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Circonstance[]    findAll()
 * @method Circonstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CirconstanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Circonstance::class);
    }

//    /**
//     * @return Circonstance[] Returns an array of Circonstance objects
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
    public function findOneBySomeField($value): ?Circonstance
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
