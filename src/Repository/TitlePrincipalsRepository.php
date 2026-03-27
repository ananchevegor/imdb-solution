<?php

namespace App\Repository;

use App\Entity\TitlePrincipals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @extends ServiceEntityRepository<TitlePrincipals>
 */
class TitlePrincipalsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private CacheInterface $cache)
    {
        parent::__construct($registry, TitlePrincipals::class);
    }

    //    /**
    //     * @return TitlePrincipals[] Returns an array of TitlePrincipals objects
    //     */
    //    public function findByExampleField($value): array


    public function findByTconst(string $tconst) : array
    {

         return $this->cache->get('title_principals_tconst_' . $tconst, function (ItemInterface $item) use ($tconst) {
            $item->expiresAfter(3600);

            return $this->createQueryBuilder('t')
                ->where('t.tconst = :tconst')
                ->setParameter('tconst', $tconst)
                ->getQuery()
                ->getResult();
        });

    }
}
