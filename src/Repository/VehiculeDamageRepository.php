<?php

namespace App\Repository;

use App\Entity\VehiculeDamage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VehiculeDamage|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehiculeDamage|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehiculeDamage[]    findAll()
 * @method VehiculeDamage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeDamageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VehiculeDamage::class);
    }

//    /**
//     * @return VehiculeDamages[] Returns an array of VehiculeDamages objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VehiculeDamages
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
