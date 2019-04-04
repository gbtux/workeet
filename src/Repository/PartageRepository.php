<?php

namespace App\Repository;

use App\Entity\Partage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Partage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partage[]    findAll()
 * @method Partage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Partage::class);
    }

    // /**
    //  * @return Partage[] Returns an array of Partage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Partage
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
