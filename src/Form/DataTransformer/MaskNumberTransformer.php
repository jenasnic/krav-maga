<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements DataTransformerInterface<string|float|int, string>
 */
class MaskNumberTransformer implements DataTransformerInterface
{
    public function __construct(protected int $floatPrecision)
    {
    }

    public function transform($data)
    {
        return (string) $data;
    }

    public function reverseTransform($data)
    {
        if (null === $data) {
            return 0;
        }

        $number = preg_replace('/[^0-9,\.]/', '', $data);
        if (null === $number) {
            return 0;
        }

        $number = str_replace(',', '.', $number);
        if (is_numeric($number)) {
            return round((float) $number, $this->floatPrecision);
        }

        return 0;
    }
}
