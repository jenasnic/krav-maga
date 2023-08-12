<?php

namespace App\Form\DataMapper\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\PassPayment;
use App\Entity\Season;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PassPaymentDataMapper implements DataMapperInterface
{
    public function __construct(
        protected Adherent $adherent,
        protected Season $season,
    ) {
    }

    /**
     * @param PassPayment|null $viewData
     */
    public function mapDataToForms($viewData, \Traversable $forms): void
    {
        if (!$viewData instanceof PassPayment) {
            return;
        }

        $forms = iterator_to_array($forms);

        $forms['amount']->setData($viewData->getAmount());
        $forms['date']->setData($viewData->getDate());
        $forms['comment']->setData($viewData->getComment());
        $forms['number']->setData($viewData->getNumber());
    }

    /**
     * @param PassPayment|null $viewData
     */
    public function mapFormsToData(\Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        try {
            if (null === $viewData) {
                $viewData = new PassPayment($this->adherent, $this->season);
            }

            /** @var float|null $amount */
            $amount = $forms['amount']->getData();
            /** @var \DateTime|null $date */
            $date = $forms['date']->getData();
            /** @var string|null $comment */
            $comment = $forms['comment']->getData();
            /** @var string|null $number */
            $number = $forms['number']->getData();

            $viewData->setAmount($amount);
            $viewData->setComment($comment);
            $viewData->setNumber($number);
            if (null !== $date) {
                $viewData->setDate($date);
            }
        } catch (\Exception $e) {
            throw new TransformationFailedException('Unable to map data for pass payment', 0, $e);
        }
    }
}
