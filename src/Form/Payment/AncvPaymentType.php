<?php

namespace App\Form\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\AncvPayment;
use App\Entity\Season;
use App\Form\DataMapper\Payment\AncvPaymentDataMapper;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AncvPaymentType extends AbstractPaymentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('number', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => AncvPayment::class,
            'label_format' => 'form.payment.ancv.%name%',
        ]);
    }

    protected function getDataMapper(Adherent $adherent, Season $season): DataMapperInterface
    {
        return new AncvPaymentDataMapper($adherent, $season);
    }
}
