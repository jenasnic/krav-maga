<?php

namespace App\Form\Payment;

use App\Entity\Adherent;
use App\Entity\Season;
use App\Form\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class)
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('comment', TextareaType::class)
        ;

        /** @var Adherent $adherent */
        $adherent = $options['adherent'];
        /** @var Season $season */
        $season = $options['season'];

        $builder->setDataMapper($this->getDataMapper($adherent, $season));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['adherent', 'season']);
        $resolver->setAllowedTypes('adherent', Adherent::class);
        $resolver->setAllowedTypes('season', Season::class);

        $resolver->setDefaults([
            'empty_data' => null,
        ]);
    }

    abstract protected function getDataMapper(Adherent $adherent, Season $season): DataMapperInterface;
}
