<?php

namespace App\Form\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\TransferPayment;
use App\Entity\Season;
use App\Form\DataMapper\Payment\TransferPaymentDataMapper;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferPaymentType extends AbstractPaymentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('label', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => TransferPayment::class,
            'label_format' => 'form.payment.transfer.%name%',
        ]);
    }

    protected function getDataMapper(Adherent $adherent, Season $season): DataMapperInterface
    {
        return new TransferPaymentDataMapper($adherent, $season);
    }
}
