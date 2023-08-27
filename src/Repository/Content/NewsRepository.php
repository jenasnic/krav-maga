<?php

namespace App\Repository\Content;

use App\Entity\Content\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function findOrdered(): array
    {
        /** @var array<News> */
        return $this
            ->createQueryBuilder('news')
            ->andWhere('news.active = TRUE')
            ->orderBy('news.rank ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
