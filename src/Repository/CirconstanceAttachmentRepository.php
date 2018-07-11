<?php

namespace App\Repository;

use App\Entity\CirconstanceAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CirconstanceAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method CirconstanceAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method CirconstanceAttachment[]    findAll()
 * @method CirconstanceAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CirconstanceAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CirconstanceAttachment::class);
    }

//    /**
//     * @return CirconstanceAttachment[] Returns an array of CirconstanceAttachment objects
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
    public function findOneBySomeField($value): ?CirconstanceAttachment
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
