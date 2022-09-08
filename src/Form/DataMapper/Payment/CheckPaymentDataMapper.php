<?php

namespace App\Form\DataMapper\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\CheckPayment;
use App\Entity\Season;
use DateTime;
use Exception;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Traversable;

class CheckPaymentDataMapper implements DataMapperInterface
{
    public function __construct(
        protected Adherent $adherent,
        protected Season $season,
    ) {
    }

    /**
     * @param CheckPayment|null $viewData
     */
    public function mapDataToForms($viewData, Traversable $forms): void
    {
        if (!$viewData instanceof CheckPayment) {
            return;
        }

        $forms = iterator_to_array($forms);

        $forms['amount']->setData($viewData->getAmount());
        $forms['date']->setData($viewData->getDate());
        $forms['comment']->setData($viewData->getComment());
        $forms['number']->setData($viewData->getNumber());
        $forms['cashingDate']->setData($viewData->getCashingDate());
    }

    /**
     * @param CheckPayment|null $viewData
     */
    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        try {
            if (null === $viewData) {
                $viewData = new CheckPayment($this->adherent, $this->season);
            }

            /** @var float|null $amount */
            $amount = $forms['amount']->getData();
            /** @var DateTime|null $date */
            $date = $forms['date']->getData();
            /** @var string|null $comment */
            $comment = $forms['comment']->getData();
            /** @var string|null $number */
            $number = $forms['number']->getData();
            /** @var DateTime|null $cashingDate */
            $cashingDate = $forms['cashingDate']->getData();

            $viewData->setAmount($amount);
            $viewData->setComment($comment);
            $viewData->setNumber($number);
            $viewData->setCashingDate($cashingDate);
            if (null !== $date) {
                $viewData->setDate($date);
            }
        } catch (Exception $e) {
            throw new TransformationFailedException('Unable to map data for check payment', 0, $e);
        }
    }
}
