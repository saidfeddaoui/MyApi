<?php
/**
 * Created by PhpStorm.
 * User: mobiblanc
 * Date: 04/09/2018
 * Time: 11:49
 */

namespace App\Repository;


class NotificationRepository
{
    public function getPushClient()
    {
        return $this->createQueryBuilder('p')
            ->where('p.status = :val')
            ->andWhere('p.client is not null')
            ->setParameter('val', false)
            ->orderBy('p.created_at', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}