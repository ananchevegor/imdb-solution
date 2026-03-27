<?php

namespace App\Repository;

use App\Entity\NameBasics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NameBasics>
 */
class NameBasicsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NameBasics::class);
    }

    //    /**
    //     * @return NameBasics[] Returns an array of NameBasics objects
    //     */
    public function findByNconst(string $nconst): ?NameBasics
    {
        return $this->createQueryBuilder('n')
            ->where('n.nconst IN(:nconst)')
            ->setParameter('nconst', $nconst)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
