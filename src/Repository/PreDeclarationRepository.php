<?php

namespace App\Repository;

use App\Entity\InsuranceType;
use App\Entity\PreDeclaration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PreDeclaration|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreDeclaration|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreDeclaration[]    findAll()
 * @method PreDeclaration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreDeclarationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PreDeclaration::class);
    }

    /**
     * @param int $status
     * @param int|InsuranceType $insuranceType
     * @return PreDeclaration[] Returns an array of PreDeclaration objects
     */
    public function findByStatusAndInsuranceType(int $status, $insuranceType)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.status = :status')
            ->andwhere('p.insuranceType = :insuranceType')
            ->setParameter('status', $status)
            ->setParameter('insuranceType', $insuranceType)
            ->orderBy('p.createdAt', 'DESC')
        ;
        return $query->getQuery()->getResult();
    }



    /**
     * @param int $status
     * @param int|InsuranceType $insuranceType
     * @return PreDeclaration[] Returns an array of PreDeclaration objects
     */
    public function findByInsuranceTypeOrderByPredeclaration($insuranceType)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.insuranceType = :insuranceType')
            ->setParameter('insuranceType', $insuranceType)
            ->orderBy('p.createdAt', 'DESC')
        ;
        return $query->getQuery()->getResult();
    }


    /*
    public function findOneBySomeField($value): ?PreDeclaration
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
