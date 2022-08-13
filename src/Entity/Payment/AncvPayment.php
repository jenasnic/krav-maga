<?php

namespace App\Entity\Payment;

use App\Enum\PaymentTypeEnum;
use App\Repository\Payment\AncvPaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AncvPaymentRepository::class)]
#[ORM\Table(name: 'payment_ancv')]
class AncvPayment extends AbstractPayment
{
    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotBlank]
    private ?string $number = null;

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getPaymentType(): string
    {
        return PaymentTypeEnum::ANCV;
    }
}
