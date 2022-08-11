<?php

namespace App\Repository\Payment;

use App\Entity\Payment\AncvPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AncvPayment>
 */
class AncvPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AncvPayment::class);
    }
}
