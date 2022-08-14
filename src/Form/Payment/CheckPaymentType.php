<?php

namespace App\Form\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\CheckPayment;
use App\Entity\Season;
use App\Form\DataMapper\Payment\CheckPaymentDataMapper;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckPaymentType extends AbstractPaymentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('number', TextType::class);
        $builder->add('cashingDate', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => CheckPayment::class,
            'label_format' => 'form.payment.check.%name%',
        ]);
    }

    protected function getDataMapper(Adherent $adherent, Season $season): DataMapperInterface
    {
        return new CheckPaymentDataMapper($adherent, $season);
    }
}
