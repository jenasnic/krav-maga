<?php

namespace App\Repository\Content;

use App\Entity\Content\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function add(News $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(News $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<News>
     */
    public function findAllOrdered(): array
    {
        /** @var array<News> */
        return $this
            ->createQueryBuilder('news')
            ->orderBy('news.rank', Criteria::ASC)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<News>
     */
    public function findOrdered(): array
    {
        /** @var array<News> */
        return $this
            ->createQueryBuilder('news')
            ->andWhere('news.active = TRUE')
            ->orderBy('news.rank', Criteria::ASC)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFirstRank(): int
    {
        $query = $this
            ->createQueryBuilder('news')
            ->select('MIN(news.rank)')
            ->getQuery()
        ;

        /** @var int $minRank */
        $minRank = $query->getSingleScalarResult() ?? 0;

        return $minRank;
    }
}
