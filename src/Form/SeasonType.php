<?php

namespace App\Form;

use App\Entity\Season;
use App\Form\Payment\PriceOptionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Season $season */
            $season = $event->getData();

            $event->getForm()->add('priceOptions', CollectionType::class, [
                'label' => false,
                'entry_type' => PriceOptionType::class,
                'entry_options' => [
                    'label' => false,
                    'season' => $season,
                ],
                'block_prefix' => 'season_price_option_list',
                'allow_add' => true,
                'allow_delete' => true,
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
            'label_format' => 'back.season.form.%name%',
        ]);
    }
}
