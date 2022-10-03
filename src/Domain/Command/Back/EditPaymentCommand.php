<?php

namespace App\Domain\Command\Back;

use App\Entity\Payment\AbstractPayment;

class EditPaymentCommand
{
    public function __construct(public AbstractPayment $payment)
    {
    }
}
