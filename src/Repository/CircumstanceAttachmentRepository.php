<?php

namespace App\Repository;

use App\Entity\CircumstanceAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CircumstanceAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method CircumstanceAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method CircumstanceAttachment[]    findAll()
 * @method CircumstanceAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CircumstanceAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CircumstanceAttachment::class);
    }

    /**
     * @return CircumstanceAttachment[] Returns an array of CircumstanceAttachment objects
     */
    public function findByIds(array $ids)
    {
        return $this->createQueryBuilder('c')
               ->where('c.id in (:ids) ')
               ->setParameter('ids',$ids)
               ->getQuery()
               ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?CircumstanceAttachment
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
