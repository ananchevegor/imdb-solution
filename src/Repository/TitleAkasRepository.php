<?php

namespace App\Repository;

use App\Entity\TitleAkas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @extends ServiceEntityRepository<TitleAkas>
 */
class TitleAkasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private CacheInterface $cache)
    {
        parent::__construct($registry, TitleAkas::class);
    }

    //    /**
    //     * @return TitleAkas[] Returns an array of TitleAkas objects
    //     */
    
    public function finById(string $id): ?TitleAkas
    {
        return $this->cache->get('title_akas_id_' . $id, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(3600);

            return $this->createQueryBuilder('t')
                ->where('t.title_id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();
        });
    }


}
