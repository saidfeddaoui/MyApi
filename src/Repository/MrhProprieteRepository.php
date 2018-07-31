<?php

namespace App\Repository;

use App\Entity\MrhPropriete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MrhPropriete|null find($id, $lockMode = null, $lockVersion = null)
 * @method MrhPropriete|null findOneBy(array $criteria, array $orderBy = null)
 * @method MrhPropriete[]    findAll()
 * @method MrhPropriete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MrhProprieteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MrhPropriete::class);
    }

//    /**
//     * @return MrhPropriete[] Returns an array of MrhPropriete objects
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
    public function findOneBySomeField($value): ?MrhPropriete
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
