<?php

namespace App\Entity\Payment;

use App\Enum\PaymentTypeEnum;
use App\Repository\Payment\HelloAssoPaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HelloAssoPaymentRepository::class)]
#[ORM\Table(name: 'payment_hello_asso')]
class HelloAssoPayment extends AbstractPayment
{
    public function getPaymentType(): string
    {
        return PaymentTypeEnum::HELLO_ASSO;
    }
}
