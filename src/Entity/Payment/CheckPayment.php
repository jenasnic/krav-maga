<?php

namespace App\Entity\Payment;

use App\Enum\PaymentTypeEnum;
use App\Repository\Payment\CheckPaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CheckPaymentRepository::class)]
#[ORM\Table(name: 'payment_check')]
class CheckPayment extends AbstractPayment
{
    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotBlank]
    private ?string $number = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?\DateTime $cashingDate = null;

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCashingDate(): ?\DateTime
    {
        return $this->cashingDate;
    }

    public function setCashingDate(?\DateTime $cashingDate): self
    {
        $this->cashingDate = $cashingDate;

        return $this;
    }

    public function getPaymentType(): string
    {
        return PaymentTypeEnum::CHECK;
    }
}
