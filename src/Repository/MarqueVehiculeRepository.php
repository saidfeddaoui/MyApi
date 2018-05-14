<?php

namespace App\Repository;

use App\Entity\MarqueVehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MarqueVehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarqueVehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarqueVehicule[]    findAll()
 * @method MarqueVehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarqueVehiculeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MarqueVehicule::class);
    }

//    /**
//     * @return MarqueVehicule[] Returns an array of MarqueVehicule objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MarqueVehicule
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
