<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function findByDates(?\DateTimeImmutable $startAt, ?\DateTimeImmutable $endAt) : Collection
    {
        if ($startAt === null && $endAt === null) {
            throw new \InvalidArgumentException('Start or end dates must be set');
        }
       $queryBuilder = $this->createQueryBuilder('c')
            ->select('c.*');
       if ($startAt instanceof \DateTimeImmutable) {
           $queryBuilder->andWhere('c.start_at >= :start')
               ->setParameter('start', $startAt);
       }
       if ($endAt instanceof \DateTimeImmutable) {
           $queryBuilder->andWhere('c.end_at <= :end')
               ->setParameter('end', $endAt);
       }

       return $queryBuilder->getQuery()->getResult();
    }

}
