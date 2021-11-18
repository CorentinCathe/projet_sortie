<?php

namespace App\Repository;

use App\Data\SearchDataSite;
use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Site|null find($id, $lockMode = null, $lockVersion = null)
 * @method Site|null findOneBy(array $criteria, array $orderBy = null)
 * @method Site[]    findAll()
 * @method Site[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Site::class);
    }

    public function findSearch(SearchDataSite $search, UserInterface $user) : array
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
