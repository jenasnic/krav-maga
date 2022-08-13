<?php

namespace App\Entity\Payment;

use App\Enum\PaymentTypeEnum;
use App\Repository\Payment\CashPaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CashPaymentRepository::class)]
#[ORM\Table(name: 'payment_cash')]
class CashPayment extends AbstractPayment
{
    public function getPaymentType(): string
    {
        return PaymentTypeEnum::CASH;
    }
}
