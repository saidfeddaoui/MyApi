<?php

namespace App\Repository;

use App\Entity\AssistanceRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AssistanceRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssistanceRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssistanceRequest[]    findAll()
 * @method AssistanceRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssistanceRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AssistanceRequest::class);
    }

//    /**
//     * @return AssistanceRequest[] Returns an array of AssistanceRequest objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssistanceRequest
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
