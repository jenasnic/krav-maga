<?php

namespace App\Form\Extension;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SubmitFormTypeExtension extends ButtonFormTypeExtension
{
    /**
     * @return string[]
     */
    public static function getExtendedTypes(): iterable
    {
        return [SubmitType::class];
    }
}
