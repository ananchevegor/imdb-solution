<?php


namespace App\Repository;

use App\Entity\TitleRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class TitleRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private CacheInterface $cache)
    {
        parent::__construct($registry, TitleRating::class);
    }

    public function fetchRatings(): array
    {
        return $this->createQueryBuilder('itr')
            ->select('itr.tconst, itr.averageRating, itr.numVotes')
            ->setMaxResults(100)
            ->getQuery()
            ->getArrayResult();
    }

    public function fetchRatingByTconst(string $tconst): ?TitleRating
    {
        return $this->cache->get('title_rating_tconst_' . $tconst, function (ItemInterface $item) use ($tconst) {
            $item->expiresAfter(3600);
            return $this->createQueryBuilder('itr')
                ->where('itr.tconst = :tconst')
                ->setParameter('tconst', $tconst)
                ->getQuery()
                ->getOneOrNullResult();
        });
    }
}
