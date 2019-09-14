<?php

namespace App\Repository;

use App\Entity\MetaCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MetaCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MetaCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MetaCategory[]    findAll()
 * @method MetaCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetaCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MetaCategory::class);
    }

    // /**
    //  * @return MetaCategory[] Returns an array of MetaCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MetaCategory
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
