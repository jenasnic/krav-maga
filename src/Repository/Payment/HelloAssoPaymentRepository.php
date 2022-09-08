<?php

namespace App\Repository\Payment;

use App\Entity\Payment\HelloAssoPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HelloAssoPayment>
 */
class HelloAssoPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HelloAssoPayment::class);
    }
}
