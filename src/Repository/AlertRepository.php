<?php

namespace App\Repository;

use App\Entity\Alert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Alert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alert[]    findAll()
 * @method Alert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Alert::class);
    }

     /**
     * @return Alert[] Returns an array of Alert objects
     */
    public function getCurrentAlerts($insuranceType)
    {
        dump(date('Y-m-d H:i:m'));
        die();
        return $this->createQueryBuilder('a')
            ->Where('a.date_expiration > :today or a.date_expiration is null')
            ->andWhere('a.insuranceType = :insuranceType')
            ->andWhere('a.date_creation <= :today')
            ->setParameter('today', date('Y-m-d H:i:m'))
            ->setParameter('insuranceType', $insuranceType)
            ->getQuery()
            ->getResult()
            ;
    }
}
