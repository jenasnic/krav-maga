<?php

namespace App\Repository;

use App\Entity\Season;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Season>
 */
class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    public function add(Season $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Season $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<Season>
     */
    public function search(): array
    {
        /** @var array<Season> */
        return $this
            ->createQueryBuilder('season')
            ->addOrderBy('season.startDate', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function existForYear(?string $year = null): bool
    {
        if (null === $year) {
            $year = (new DateTime())->format('Y');
        }

        $queryBuilder = $this->createQueryBuilder('season');

        $queryBuilder
            ->select('COUNT(season)')
            ->andWhere('season.label = :year')
            ->setParameter('year', $year)
        ;

        return $queryBuilder->getQuery()->getSingleScalarResult() > 0;
    }

    public function getActiveSeason(): ?Season
    {
        /** @var Season|null */
        return $this
            ->createQueryBuilder('season')
            ->andWhere('season.active = TRUE')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
