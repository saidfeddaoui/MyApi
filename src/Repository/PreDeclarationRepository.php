<?php

namespace App\Repository;

use App\Entity\PreDeclaration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PreDeclaration|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreDeclaration|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreDeclaration[]    findAll()
 * @method PreDeclaration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreDeclarationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PreDeclaration::class);
    }

//    /**
//     * @return PreDeclaration[] Returns an array of PreDeclaration objects
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
    public function findOneBySomeField($value): ?PreDeclaration
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
