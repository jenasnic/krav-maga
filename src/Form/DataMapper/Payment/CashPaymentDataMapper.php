<?php

namespace App\Form\DataMapper\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\CashPayment;
use App\Entity\Season;
use Exception;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Traversable;

class CashPaymentDataMapper implements DataMapperInterface
{
    public function __construct(
        protected Adherent $adherent,
        protected Season $season,
    ) {
    }

    /**
     * @param CashPayment|null $viewData
     */
    public function mapDataToForms($viewData, Traversable $forms): void
    {
        if (!$viewData instanceof CashPayment) {
            return;
        }

        $forms = iterator_to_array($forms);

        $forms['amount']->setData($viewData->getAmount());
        $forms['comment']->setData($viewData->getComment());
    }

    /**
     * @param CashPayment|null $viewData
     */
    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        try {
            if (null === $viewData) {
                $viewData = new CashPayment($this->adherent, $this->season);
            }

            /** @var float|null $amount */
            $amount = $forms['amount']->getData();
            /** @var string|null $comment */
            $comment = $forms['comment']->getData();

            $viewData->setAmount($amount);
            $viewData->setComment($comment);
        } catch (Exception $e) {
            throw new TransformationFailedException('Unable to map data for cash payment', 0, $e);
        }
    }
}
