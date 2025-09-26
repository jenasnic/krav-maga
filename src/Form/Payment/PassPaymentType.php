<?php

namespace App\Form\Payment;

use App\Entity\Adherent;
use App\Entity\Payment\PassPayment;
use App\Entity\Season;
use App\Form\DataMapper\Payment\PassPaymentDataMapper;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassPaymentType extends AbstractPaymentType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('amount', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'enum.passSport.PASS_CITIZEN' => 15,
                    'enum.passSport.PASS_SPORT' => 70,
                    'enum.passSport.CCAS' => 10,
                ],
            ])
            ->add('number', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => PassPayment::class,
            'label_format' => 'form.payment.pass.%name%',
        ]);
    }

    protected function getDataMapper(Adherent $adherent, Season $season): DataMapperInterface
    {
        return new PassPaymentDataMapper($adherent, $season);
    }
}
