<?php

namespace App\Repository;

use App\Entity\DevisAuto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DevisAuto|null find($id, $lockMode = null, $lockVersion = null)
 * @method DevisAuto|null findOneBy(array $criteria, array $orderBy = null)
 * @method DevisAuto[]    findAll()
 * @method DevisAuto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisAutoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DevisAuto::class);
    }

//    /**
//     * @return DevisAuto[] Returns an array of DevisAuto objects
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
    public function findOneBySomeField($value): ?DevisAuto
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
