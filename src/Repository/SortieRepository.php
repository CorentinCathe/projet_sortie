<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Entity\User;
use DateTime;
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
            ->join('s.site','site')
        ;

        switch ([!empty($search->isOrga),!empty($search->isInscrit),!empty($search->isNotInscrit),!empty($search->isFinished)]) {
            case [true, true,true,true]:
                break;
            case [false, false,false,false]:
                break;
            case [true, false,false,false]:
                $query = $query
                    ->andWhere('s.organisator = :orga')
                    ->setParameter('orga', $user->getId())
                    ->andWhere('s.dateHourStart > :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
            ;
                break;
            case [false, true,false,false]:
                $query = $query
                    ->andWhere('s.organisator != :orga')
                    ->setParameter('orga', $user->getId())
                    ->andWhere('s.id in (Select s5.id from App\Entity\Sortie s5 inner join s5.user user5 where user5.id = :u)')
                    ->setParameter('u', $user->getId())
                    ->andWhere('s.dateHourStart > :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                    ;
                break;
            case [false, false,true,false]:
                $query = $query
                    ->andWhere('s.organisator != :orga')
                    ->setParameter('orga', $user->getId())
                    ->andWhere('s.id not in (Select s5.id from App\Entity\Sortie s5 inner join s5.user user5 where user5.id = :u)')
                    ->setParameter('u', $user->getId())
                    ->andWhere('s.dateHourStart > :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                ;
                break;
            case [false, false,false,true]:
                $query = $query
                    ->andWhere('s.dateHourStart <= :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                    ;
                break;
            case [true, true,false,false]:
                $query = $query
                    ->orWhere('s.organisator = :orga')
                    ->setParameter('orga', $user->getId())
                    ->orWhere('s.id in (Select s5.id from App\Entity\Sortie s5 inner join s5.user user5 where user5.id = :u)')
                    ->setParameter('u', $user->getId())
                    ->andWhere('s.dateHourStart > :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                ;
                break;
            case [true, false,true,false]:
                $query = $query
                    ->orWhere('s.organisator = :orga')
                    ->setParameter('orga', $user->getId())
                    ->orWhere('s.id not in (Select s5.id from App\Entity\Sortie s5 inner join s5.user user5 where user5.id = :u)')
                    ->setParameter('u', $user->getId())
                    ->andWhere('s.dateHourStart > :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                ;
                break;
            case [true, false,false,true]:
                $query = $query
                    ->orWhere('s.organisator = :orga')
                    ->setParameter('orga', $user->getId())
                    ->orWhere('s.dateHourStart <= :date')
                    ->setParameter('date', date('Y-m-d H:i:s'));
                ;
                break;
            case [false, true,true,false]:
                $query = $query
                    ->andWhere('s.organisator != :orga')
                    ->setParameter('orga', $user->getId())
                    ->andWhere('s.dateHourStart >= :date')
                    ->setParameter('date', date('Y-m-d H:i:s'));
                break;
            case [false, true,false,true]:
                $query = $query
                    ->andWhere('s.id in (Select s5.id from App\Entity\Sortie s5 inner join s5.user user5 where user5.id = :u)')
                    ->setParameter('u', $user->getId())
                    ->orWhere('s.dateHourStart <= :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                    ->andWhere('s.organisator != :orga')
                    ->setParameter('orga', $user->getId())
                    ;
                //dd($query->getDQL());
                break;
            case [false, false,true,true]:
                $query = $query
                    ->orWhere('s.id not in (Select s5.id from App\Entity\Sortie s5 inner join s5.user user5 where user5.id = :u)')
                    ->setParameter('u', $user->getId())
                    ->orWhere('s.dateHourStart <= :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                    ->andWhere('s.organisator != :orga')
                    ->setParameter('orga', $user->getId())
                ;
                break;
            case [true, true,true,false]:
                $query = $query
                    ->orWhere('s.dateHourStart >= :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                    ;
                break;
            case [false, true,true,true]:
                $query = $query
                    ->andWhere('s.organisator != :orga')
                    ->setParameter('orga', $user->getId())
                    ;
                break;
            case [true, false,true,true]:
                $query = $query
                    ->andWhere('s.id not in (Select s5.id from App\Entity\Sortie s5 inner join s5.user user5 where user5.id = :u)')
                    ->setParameter('u', $user->getId())
                    ;
                break;
            case [true, true,false,true]:
                $query = $query
                    ->orWhere('s.organisator = :orga')
                    ->setParameter('orga', $user->getId())
                    ->orWhere('s.id in (Select s5.id from App\Entity\Sortie s5 inner join s5.user user5 where user5.id = :u)')
                    ->setParameter('u', $user->getId())
                    ->orWhere('s.dateHourStart <= :date')
                    ->setParameter('date', date('Y-m-d H:i:s'))
                ;
                break;
        }

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


        //dd($query->getDQL());
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
