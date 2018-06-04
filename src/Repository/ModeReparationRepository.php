<?php

namespace App\Repository;

use App\Entity\ModeReparation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ModeReparation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeReparation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeReparation[]    findAll()
 * @method ModeReparation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeReparationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModeReparation::class);
    }

//    /**
//     * @return ModeReparation[] Returns an array of ModeReparation objects
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
    public function findOneBySomeField($value): ?ModeReparation
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
