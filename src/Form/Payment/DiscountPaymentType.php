<?php

namespace App\Form\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\DiscountPayment;
use App\Entity\Season;
use App\Form\DataMapper\Payment\DiscountPaymentDataMapper;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountPaymentType extends AbstractPaymentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('discount', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => DiscountPayment::class,
            'label_format' => 'form.payment.discount.%name%',
        ]);
    }

    protected function getDataMapper(Adherent $adherent, Season $season): DataMapperInterface
    {
        return new DiscountPaymentDataMapper($adherent, $season);
    }
}
