<?php

namespace App\Form\DataMapper\Payment;

use App\Entity\Payment\PriceOption;
use App\Entity\Season;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PriceOptionDataMapper implements DataMapperInterface
{
    public function __construct(private readonly Season $season)
    {
    }

    /**
     * @param PriceOption|null $viewData
     */
    public function mapDataToForms(mixed $viewData, \Traversable $forms): void
    {
        if (!$viewData instanceof PriceOption) {
            return;
        }

        $forms = iterator_to_array($forms);

        $forms['label']->setData($viewData->getLabel());
        $forms['amount']->setData($viewData->getAmount());
        $forms['rank']->setData($viewData->getRank());
    }

    /**
     * @param PriceOption|null $viewData
     */
    public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
    {
        $forms = iterator_to_array($forms);

        try {
            /** @var string $label */
            $label = $forms['label']->getData();
            /** @var float $amount */
            $amount = $forms['amount']->getData();
            /** @var int $rank */
            $rank = $forms['rank']->getData();

            if (null === $viewData) {
                $viewData = new PriceOption($label, $amount, $this->season);
                $viewData->setRank($rank);

                return;
            }

            $viewData->setLabel($label);
            $viewData->setAmount($amount);
            $viewData->setRank($rank);
        } catch (\Exception $e) {
            throw new TransformationFailedException('Unable to map data for pass payment', 0, $e);
        }
    }
}
