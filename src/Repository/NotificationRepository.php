<?php
/**
 * Created by PhpStorm.
 * User: mobiblanc
 * Date: 04/09/2018
 * Time: 11:49
 */

namespace App\Repository;
use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function getPushClient()
    {
        return $this->createQueryBuilder('p')
            ->where('p.statut = :val')
            ->andWhere('p.client is not null')
            ->setParameter('val', false)
            ->orderBy('p.dateCreation', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}