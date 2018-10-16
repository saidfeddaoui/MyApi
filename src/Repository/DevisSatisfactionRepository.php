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

    public function findAllByOrder()
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.devisAuto', 'a')
            ->leftJoin('d.devisHabitation', 'h')
            ->orderBy('a.createdAt', 'DESC')
            ->addOrderBy('h.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


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
