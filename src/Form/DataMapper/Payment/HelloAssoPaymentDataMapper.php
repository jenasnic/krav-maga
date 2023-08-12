<?php

namespace App\Form\DataMapper\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\HelloAssoPayment;
use App\Entity\Season;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class HelloAssoPaymentDataMapper implements DataMapperInterface
{
    public function __construct(
        protected Adherent $adherent,
        protected Season $season,
    ) {
    }

    /**
     * @param HelloAssoPayment|null $viewData
     */
    public function mapDataToForms($viewData, \Traversable $forms): void
    {
        if (!$viewData instanceof HelloAssoPayment) {
            return;
        }

        $forms = iterator_to_array($forms);

        $forms['amount']->setData($viewData->getAmount());
        $forms['date']->setData($viewData->getDate());
        $forms['comment']->setData($viewData->getComment());
    }

    /**
     * @param HelloAssoPayment|null $viewData
     */
    public function mapFormsToData(\Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        try {
            if (null === $viewData) {
                $viewData = new HelloAssoPayment($this->adherent, $this->season);
            }

            /** @var float|null $amount */
            $amount = $forms['amount']->getData();
            /** @var \DateTime|null $date */
            $date = $forms['date']->getData();
            /** @var string|null $comment */
            $comment = $forms['comment']->getData();

            $viewData->setAmount($amount);
            $viewData->setComment($comment);
            if (null !== $date) {
                $viewData->setDate($date);
            }
        } catch (\Exception $e) {
            throw new TransformationFailedException('Unable to map data for Hello Asso payment', 0, $e);
        }
    }
}
