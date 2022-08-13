<?php

namespace App\Form\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\CashPayment;
use App\Entity\Season;
use App\Form\DataMapper\Payment\CashPaymentDataMapper;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CashPaymentType extends AbstractPaymentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CashPayment::class,
            'label_format' => 'form.payment.cash.%name%',
        ]);
    }

    protected function getDataMapper(Adherent $adherent, Season $season): DataMapperInterface
    {
        return new CashPaymentDataMapper($adherent, $season);
    }
}
