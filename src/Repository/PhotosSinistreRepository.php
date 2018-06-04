<?php

namespace App\Repository;

use App\Entity\PhotosSinistre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhotosSinistre|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotosSinistre|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotosSinistre[]    findAll()
 * @method PhotosSinistre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotosSinistreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhotosSinistre::class);
    }

//    /**
//     * @return PhotosSinistre[] Returns an array of PhotosSinistre objects
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
    public function findOneBySomeField($value): ?PhotosSinistre
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
