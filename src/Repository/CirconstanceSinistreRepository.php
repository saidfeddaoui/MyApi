<?php

namespace App\Repository;

use App\Entity\CirconstanceSinistre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CirconstanceSinistre|null find($id, $lockMode = null, $lockVersion = null)
 * @method CirconstanceSinistre|null findOneBy(array $criteria, array $orderBy = null)
 * @method CirconstanceSinistre[]    findAll()
 * @method CirconstanceSinistre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CirconstanceSinistreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CirconstanceSinistre::class);
    }

//    /**
//     * @return CirconstanceSinistre[] Returns an array of CirconstanceSinistre objects
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
    public function findOneBySomeField($value): ?CirconstanceSinistre
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
