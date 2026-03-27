<?php

namespace App\Repository;

use App\Entity\TitleBasics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @extends ServiceEntityRepository<TitleBasics>
 */
class TitleBasicsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private CacheInterface $cache)
    {
        parent::__construct($registry, TitleBasics::class);
    }

    public function findAllOffset(int $offset, int $limit): array
    {
        return $this->cache->get('title_basics_offset_' . $offset . '_' . $limit, function (ItemInterface $item) use ($offset, $limit) {
            $item->expiresAfter(3600);

            return $this->createQueryBuilder('t')
                ->orderBy('t.tconst', 'DESC')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        });
    }

    public function findByCursor(string $cursor, int $limit, string $direction): array
    {
        $cacheKey = 'title_basics_cursor_' . $direction . '_' . $cursor . '_' . $limit;

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($cursor, $limit, $direction) {
            $item->expiresAfter(3600);
            $queryBuilder = $this->createQueryBuilder('t');

            if ($direction === 'prev') {
                $results = $queryBuilder
                    ->leftJoin('t.rating', 'tr')
                    ->addSelect('tr')
                    ->where('t.tconst > :cursor')
                    ->setParameter('cursor', $cursor)
                    ->orderBy('t.tconst', 'ASC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();

                return array_reverse($results);
            }

            return $queryBuilder
                ->leftJoin('t.rating', 'tr')
                ->addSelect('tr')
                ->where('t.tconst < :cursor')
                ->setParameter('cursor', $cursor)
                ->orderBy('t.tconst', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        });
    }

    public function fetchFirstPage(int $limit): array
    {
        return $this->cache->get('title_basics_first_page_' . $limit, function (ItemInterface $item) use ($limit) {
            $item->expiresAfter(3600);

            return $this->createQueryBuilder('t')
                ->leftJoin('t.rating', 'tr')
                ->addSelect('tr')
                ->orderBy('t.tconst', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        });
    }

    public function fetchLastPage(int $limit): array
    {
        return $this->cache->get('title_basics_last_page_' . $limit, function (ItemInterface $item) use ($limit) {
            $item->expiresAfter(3600);

            $results = $this->createQueryBuilder('t')
                ->leftJoin('t.rating', 'tr')
                ->addSelect('tr')
                ->orderBy('t.tconst', 'ASC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();

            return array_reverse($results);
        });
    }

    public function countAll(): int
    {
        return $this->cache->get('title_basics_count', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->count([]);
        });
    }

    public function clearCache(): void
    {
        $this->cache->clear();
    }

    public function fetchFirst(): array
    {
        return $this->cache->get('title_basics_first', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->createQueryBuilder('t')
                ->orderBy('t.tconst', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();
        });
    }

    public function fetchByName(string $name): array
    {
        return $this->cache->get('title_basics_name_' . $name, function (ItemInterface $item) use ($name) {
            $item->expiresAfter(3600);

            return $this->createQueryBuilder('t')
                ->leftJoin('t.rating', 'tr')
                ->addSelect('tr')
                ->where('LOWER(t.primaryTitle) LIKE LOWER(:name)')
                ->orWhere('LOWER(t.originalTitle) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $name . '%')
                ->orderBy('t.tconst', 'DESC')
                ->setMaxResults(100)
                ->getQuery()
                ->getResult();
        });
    }

    public function fetchCountByName(string $name): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t)')
            ->where('LOWER(t.primaryTitle) LIKE LOWER(:name)')
            ->orWhere('LOWER(t.originalTitle) LIKE LOWER(:name)')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getSingleScalarResult();  
    }

    public function fetchByTconst(string $tconst): array
    {
        $queryBuilder = $this->createQueryBuilder('t');
        return $queryBuilder
            ->where('t.tconst IN(:tconst)')
            ->setParameter('tconst', $tconst)
            ->orderBy('t.tconst', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
