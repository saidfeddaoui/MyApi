<?php

namespace App\Repository;

use App\Entity\MrhBatiment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MrhBatiment|null find($id, $lockMode = null, $lockVersion = null)
 * @method MrhBatiment|null findOneBy(array $criteria, array $orderBy = null)
 * @method MrhBatiment[]    findAll()
 * @method MrhBatiment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MrhBatimentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MrhBatiment::class);
    }

//    /**
//     * @return MrhBatiment[] Returns an array of MrhBatiment objects
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
    public function findOneBySomeField($value): ?MrhBatiment
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
