<?php

namespace App\Repository\Payment;

use App\Entity\Payment\PriceOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PriceOption>
 */
class PriceOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceOption::class);
    }

    public function add(PriceOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PriceOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<PriceOption>
     */
    public function findAllOrdered(): array
    {
        /** @var array<PriceOption> */
        return $this
            ->createQueryBuilder('price_option')
            ->addOrderBy('price_option.rank')
            ->getQuery()
            ->getResult()
        ;
    }
}
