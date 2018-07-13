<?php

namespace App\Repository;

use App\Entity\TiersAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TiersAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method TiersAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method TiersAttachment[]    findAll()
 * @method TiersAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TiersAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TiersAttachment::class);
    }

    /**
     * @return TiersAttachment[] Returns an array of TiersAttachment objects
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
    public function findOneBySomeField($value): ?TiersAttachment
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
