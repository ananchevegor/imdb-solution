<?php

namespace App\Repository;

use App\Entity\TitleCrew;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
/**
 * @extends ServiceEntityRepository<TitleCrew>
 */
class TitleCrewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private CacheInterface $cache)
    {
        parent::__construct($registry, TitleCrew::class);
    }

    
    public function findByTconst(string $tconst): ?TitleCrew
    {
        return $this->createQueryBuilder('t')
            ->where('t.tconst = :tconst')
            ->setParameter('tconst', $tconst)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
