<?php

namespace App\Repository;

use App\Entity\Predeclaration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Predeclaration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Predeclaration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Predeclaration[]    findAll()
 * @method Predeclaration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PredeclarationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Predeclaration::class);
    }

//    /**
//     * @return Predeclaration[] Returns an array of Predeclaration objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Predeclaration
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
