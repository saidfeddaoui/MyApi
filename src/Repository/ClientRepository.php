<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{

    /**
     * ClientRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }
    /**
     * Load client by phone number or email
     * @param $username
     * @return Client|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByPhoneOrEmail($username): ?Client
    {
        return $this->createQueryBuilder('c')
            ->where('c.phone = :phone')
            ->orWhere('c.email = :email')
            ->setParameters(['phone' => $username, 'email' => $username])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
//    /**
//     * @return Client[] Returns an array of Client objects
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

}
