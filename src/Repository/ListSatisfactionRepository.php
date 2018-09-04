<?php

namespace App\Repository;

use App\Entity\ListSatisfaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListSatisfaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListSatisfaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListSatisfaction[]    findAll()
 * @method ListSatisfaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListSatisfactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListSatisfaction::class);
    }

//    /**
//     * @return ListSatisfaction[] Returns an array of ListSatisfaction objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListSatisfaction
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
