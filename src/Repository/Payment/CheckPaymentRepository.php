<?php

namespace App\Repository\Payment;

use App\Entity\Payment\CheckPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CheckPayment>
 */
class CheckPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckPayment::class);
    }

    /**
     * @return array<CheckPayment>
     */
    public function findCheckToCash(): array
    {
        /** @var array<CheckPayment> */
        return $this
            ->createQueryBuilder('check')
            ->andWhere('check.cashingDate IS NOT NULL')
            ->andWhere('DATE(check.cashingDate) = DATE(NOW())')
            ->getQuery()
            ->getResult()
        ;
    }
}
