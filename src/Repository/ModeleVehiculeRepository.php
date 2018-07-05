<?php

namespace App\Repository;

use App\Entity\ModeleVehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ModeleVehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeleVehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeleVehicule[]    findAll()
 * @method ModeleVehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeleVehiculeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModeleVehicule::class);
    }

//    /**
//     * @return ModeleVehicule[] Returns an array of ModeleVehicule objects
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
    public function findOneBySomeField($value): ?ModeleVehicule
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


   public function getModelesByInsurance($value)
   {
       return $this->createQueryBuilder('mo')
           ->innerJoin('mo.marque','mr')
           ->where('mo.marque = mr.id')
           ->andWhere('mr.insuranceType = :insuranceType')
           ->setParameter('insuranceType', $value)
           ->getQuery()
           ->getResult()
       ;
   }


}
