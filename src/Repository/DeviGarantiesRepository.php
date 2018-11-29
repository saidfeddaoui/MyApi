<?php

namespace App\Repository;

use App\Entity\DeviGaranties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviGaranties|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviGaranties|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviGaranties[]    findAll()
 * @method DeviGaranties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviGarantiesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviGaranties::class);
    }

//    /**
//     * @return DeviGaranties[] Returns an array of DeviGaranties objects
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
    public function findOneBySomeField($value): ?DeviGaranties
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
