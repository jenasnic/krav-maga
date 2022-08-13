<?php

namespace App\Entity\Payment;

use App\Enum\PaymentTypeEnum;
use App\Repository\Payment\PassPaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PassPaymentRepository::class)]
#[ORM\Table(name: 'payment_pass')]
class PassPayment extends AbstractPayment
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
        return PaymentTypeEnum::PASS;
    }
}
