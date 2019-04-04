<?php

namespace App\Repository;

use App\Entity\DocEvenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DocEvenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocEvenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocEvenement[]    findAll()
 * @method DocEvenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocEvenementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocEvenement::class);
    }

    // /**
    //  * @return DocEvenement[] Returns an array of DocEvenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocEvenement
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
