<?php

namespace App\Entity\Payment;

use App\Enum\PaymentTypeEnum;
use App\Repository\Payment\TransferPaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransferPaymentRepository::class)]
#[ORM\Table(name: 'payment_transfer')]
class TransferPayment extends AbstractPayment
{
    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotBlank]
    private ?string $label = null;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPaymentType(): string
    {
        return PaymentTypeEnum::TRANSFER;
    }
}
