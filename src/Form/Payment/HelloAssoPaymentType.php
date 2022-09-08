<?php

namespace App\Form\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\HelloAssoPayment;
use App\Entity\Season;
use App\Form\DataMapper\Payment\HelloAssoPaymentDataMapper;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HelloAssoPaymentType extends AbstractPaymentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => HelloAssoPayment::class,
            'label_format' => 'form.payment.helloAsso.%name%',
        ]);
    }

    protected function getDataMapper(Adherent $adherent, Season $season): DataMapperInterface
    {
        return new HelloAssoPaymentDataMapper($adherent, $season);
    }
}
