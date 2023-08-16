<?php

namespace App\Repository\Payment;

use App\Entity\Payment\AbstractPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Generator;

/**
 * @extends ServiceEntityRepository<AbstractPayment>
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractPayment::class);
    }

    public function add(AbstractPayment $payment, bool $flush = false): void
    {
        $this->getEntityManager()->persist($payment);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AbstractPayment $payment, bool $flush = false): void
    {
        $this->getEntityManager()->remove($payment);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<AbstractPayment>
     */
    public function findForAdherent(int $adherentId): array
    {
        /** @var array<AbstractPayment> */
        return $this
            ->createQueryBuilder('payment')
            ->innerJoin('payment.adherent', 'adherent')
            ->andWhere('adherent.id = :adherentId')
            ->setParameter('adherentId', $adherentId)
            ->addOrderBy('payment.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array<AbstractPayment>
     */
    public function findForSeason(int $seasonId): array
    {
        /** @var array<AbstractPayment> */
        return $this
            ->createQueryBuilder('payment')
            ->innerJoin('payment.season', 'season')
            ->andWhere('season.id = :seasonId')
            ->setParameter('seasonId', $seasonId)
            ->addOrderBy('payment.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Generator<AbstractPayment>>
     */
    public function findForExport(int $seasonId): \Generator
    {
        $result = $this->findForSeason($seasonId);

        /** @var AbstractPayment $item */
        foreach ($result as $item) {
            yield $item;
        }
    }
}
