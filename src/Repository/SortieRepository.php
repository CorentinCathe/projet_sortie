<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }
    /**
    * @return Sortie[] Returns an array of Sortie objects
    */
    public function findSearch(SearchData $search, User $user) : array {
        $query = $this
            ->createQueryBuilder('s')
            ->select('site', 's')
            ->join('s.site','site');

        if (!empty($search->q)){
            $query = $query
            ->andWhere('s.name LIKE :q')
            ->setParameter('q', "%{$search->q}%"); 
        }

        if (!empty($search->site)){
            $query = $query
            ->andWhere('site.id IN (:site)')
            ->setParameter('site', $search->site); 
        }
        if (!empty($search->debut)){
            $query = $query
            ->andWhere('s.dateHourStart >= :debut')
            ->setParameter('debut', $search->debut); 
        }
        if (!empty($search->fin)){
            $query = $query
            ->andWhere('s.dateHourStart <= :fin')
            ->setParameter('fin', $search->fin); 
        }
        if (!empty($search->isOrga)){
            $query = $query
            ->andWhere('s.organisator = :orga')
            ->setParameter('orga', $user->getId()); 
        }
        if (!empty($search->isInscrit) && !empty($search->isNotInscrit)){

        }else{
        if (!empty($search->isInscrit)){
            $sub = $this->createQueryBuilder('s2')
            ->select('s2')
            ->join('s2.user', 'user2')
            ->andWhere('user2.id in (:inscrit)')
            ->setParameter('inscrit', $user->getId()); 
            $query = $query
            ->andWhere($query->expr()->exists($sub->getDQL()))
            ->setParameter('inscrit', $user->getId()); 
        }
        if (!empty($search->isNotInscrit)){
            $sub = $this->createQueryBuilder('s4')
            ->select('s4')
            ->join('s4.user', 'user4')
            ->orWhere('user4.id in (:inscrit)')
            ->setParameter('inscrit', $user->getId()); 
            //dd($sub->getDQL());
            $query = $query
            ->orWhere('s.id not in (Select s3.id from App\Entity\Sortie s3 inner join s3.user user3)')
            // ->join('s.user', 'user')
            // ->andWhere('user.id not in (:inscrit)')
            // ->setParameter('inscrit', $user->getId())
            ->orWhere($query->expr()->not($query->expr()->exists($sub->getDQL())))
            ->setParameter('inscrit', $user->getId()); 
        }
    } 
        if (!empty($search->isFinished)){
            $query = $query
            ->orWhere('s.name LIKE :q')
            ->setParameter('q', "%{$search->q}%"); 
        }
        dd($query->getDQL());
        return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
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
    public function findOneBySomeField($value): ?Sortie
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
