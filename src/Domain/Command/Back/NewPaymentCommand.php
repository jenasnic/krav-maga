<?php

namespace App\Domain\Command\Back;

use App\Entity\Payment\AbstractPayment;

class NewPaymentCommand
{
    public ?AbstractPayment $payment = null;
}
