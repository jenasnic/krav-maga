<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class MaskNumberTransformer implements DataTransformerInterface
{
    public function __construct(protected int $floatPrecision)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($data)
    {
        if (null === $data) {
            return 0;
        }

        $number = preg_replace('/[^0-9,\.]/', '', $data);
        $number = str_replace(',', '.', $number);
        if (is_numeric($number)) {
            return round((float) $number, $this->floatPrecision);
        }

        return 0;
    }
}
