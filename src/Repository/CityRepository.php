<?php

namespace App\Repository;

use App\Data\SearchDataCity;
use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findSearch(SearchDataCity $search, UserInterface $user) : array
    {
        $query = $this
            ->createQueryBuilder('s')
            ->select('s')
        ;
        if (!empty($search->q)){
            $query = $query
                ->andWhere('s.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }
        return $query->getQuery()->getResult();
    }
}
