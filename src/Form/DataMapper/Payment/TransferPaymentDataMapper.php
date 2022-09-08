<?php

namespace App\Form\DataMapper\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\TransferPayment;
use App\Entity\Season;
use DateTime;
use Exception;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Traversable;

class TransferPaymentDataMapper implements DataMapperInterface
{
    public function __construct(
        protected Adherent $adherent,
        protected Season $season,
    ) {
    }

    /**
     * @param TransferPayment|null $viewData
     */
    public function mapDataToForms($viewData, Traversable $forms): void
    {
        if (!$viewData instanceof TransferPayment) {
            return;
        }

        $forms = iterator_to_array($forms);

        $forms['amount']->setData($viewData->getAmount());
        $forms['date']->setData($viewData->getDate());
        $forms['comment']->setData($viewData->getComment());
        $forms['label']->setData($viewData->getLabel());
    }

    /**
     * @param TransferPayment|null $viewData
     */
    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        try {
            if (null === $viewData) {
                $viewData = new TransferPayment($this->adherent, $this->season);

                /** @var float|null $amount */
                $amount = $forms['amount']->getData();
                /** @var DateTime|null $date */
                $date = $forms['date']->getData();
                /** @var string|null $comment */
                $comment = $forms['comment']->getData();
                /** @var string|null $label */
                $label = $forms['label']->getData();

                $viewData->setAmount($amount);
                $viewData->setComment($comment);
                $viewData->setLabel($label);
                if (null !== $date) {
                    $viewData->setDate($date);
                }
            }
        } catch (Exception $e) {
            throw new TransformationFailedException('Unable to map data for transfer payment', 0, $e);
        }
    }
}
