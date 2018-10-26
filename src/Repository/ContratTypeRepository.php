<?php

namespace App\Repository;

use App\Entity\ContratType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContratType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContratType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContratType[]    findAll()
 * @method ContratType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContratType::class);
    }

//    /**
//     * @return ContratType[] Returns an array of ContratType objects
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
    public function findOneBySomeField($value): ?ContratType
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
