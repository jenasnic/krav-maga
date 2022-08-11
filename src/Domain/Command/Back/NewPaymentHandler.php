<?php

namespace App\Domain\Command\Back;

use App\Repository\Payment\PaymentRepository;
use LogicException;

final class NewPaymentHandler
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
    ) {
    }

    public function handle(NewPaymentCommand $command): void
    {
        if (null === $command->payment) {
            throw new LogicException('invalid payment');
        }

        $this->paymentRepository->add($command->payment, true);
    }
}
