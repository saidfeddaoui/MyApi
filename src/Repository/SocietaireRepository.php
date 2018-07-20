<?php

namespace App\Repository;

use App\Entity\Societaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Societaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Societaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Societaire[]    findAll()
 * @method Societaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocietaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Societaire::class);
    }

//    /**
//     * @return Societaire[] Returns an array of Societaire objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Societaire
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
