<?php

namespace App\Repository;

use App\Entity\SortieArchive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SortieArchive|null find($id, $lockMode = null, $lockVersion = null)
 * @method SortieArchive|null findOneBy(array $criteria, array $orderBy = null)
 * @method SortieArchive[]    findAll()
 * @method SortieArchive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieArchiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SortieArchive::class);
    }

    // /**
    //  * @return SortieArchive[] Returns an array of SortieArchive objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SortieArchive
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
