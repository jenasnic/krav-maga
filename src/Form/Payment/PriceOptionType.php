<?php

namespace App\Form\Payment;

use App\Entity\Payment\PriceOption;
use App\Entity\Season;
use App\Form\DataMapper\Payment\PriceOptionDataMapper;
use App\Form\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Season $season */
        $season = $options['season'];

        $builder
            ->add('label', TextType::class)
            ->add('amount', NumberType::class)
            ->add('rank', HiddenType::class)
            ->setDataMapper(new PriceOptionDataMapper($season))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('season');
        $resolver->setAllowedTypes('season', Season::class);

        $resolver->setDefaults([
            'data_class' => PriceOption::class,
            'label_format' => 'back.priceOption.form.%name%',
            'empty_data' => null,
        ]);
    }
}
