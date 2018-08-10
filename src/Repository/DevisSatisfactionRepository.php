<?php

namespace App\Repository;

use App\Entity\DevisSatisfaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DevisSatisfaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevisSatisfaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevisSatisfaction[]    findAll()
 * @method DevisSatisfaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisSatisfactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DevisSatisfaction::class);
    }

//    /**
//     * @return DevisSatisfaction[] Returns an array of DevisSatisfaction objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DevisSatisfaction
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
