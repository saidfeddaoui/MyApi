<?php

namespace App\Repository;

use App\Entity\VehiculeComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VehiculeComponent|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehiculeComponent|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehiculeComponent[]    findAll()
 * @method VehiculeComponent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeComponentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VehiculeComponent::class);
    }

//    /**
//     * @return VehiculeComponents[] Returns an array of VehiculeComponents objects
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
    public function findOneBySomeField($value): ?VehiculeComponents
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
