<?php

namespace App\Repository;

use App\Entity\ProduitContrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProduitContrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitContrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitContrat[]    findAll()
 * @method ProduitContrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitContratRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProduitContrat::class);
    }

//    /**
//     * @return ProduitContrat[] Returns an array of ProduitContrat objects
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
    public function findOneBySomeField($value): ?ProduitContrat
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
