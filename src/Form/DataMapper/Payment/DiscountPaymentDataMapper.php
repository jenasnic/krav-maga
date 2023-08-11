<?php

namespace App\Form\DataMapper\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\DiscountPayment;
use App\Entity\Season;
use DateTime;
use Exception;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Traversable;

class DiscountPaymentDataMapper implements DataMapperInterface
{
    public function __construct(
        protected Adherent $adherent,
        protected Season $season,
    ) {
    }

    /**
     * @param DiscountPayment|null $viewData
     */
    public function mapDataToForms($viewData, Traversable $forms): void
    {
        if (!$viewData instanceof DiscountPayment) {
            return;
        }

        $forms = iterator_to_array($forms);

        $forms['amount']->setData($viewData->getAmount());
        $forms['date']->setData($viewData->getDate());
        $forms['comment']->setData($viewData->getComment());
        $forms['discount']->setData($viewData->getDiscount());
    }

    /**
     * @param DiscountPayment|null $viewData
     */
    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        try {
            if (null === $viewData) {
                $viewData = new DiscountPayment($this->adherent, $this->season);
            }

            /** @var float|null $amount */
            $amount = $forms['amount']->getData();
            /** @var DateTime|null $date */
            $date = $forms['date']->getData();
            /** @var string|null $comment */
            $comment = $forms['comment']->getData();
            /** @var string|null $number */
            $discount = $forms['discount']->getData();

            $viewData->setAmount($amount);
            $viewData->setComment($comment);
            $viewData->setDiscount($discount);
            if (null !== $date) {
                $viewData->setDate($date);
            }
        } catch (Exception $e) {
            throw new TransformationFailedException('Unable to map data for discount payment', 0, $e);
        }
    }
}
