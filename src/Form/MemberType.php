<?php

namespace App\Form;

use App\Entity\Member;
use App\Enum\GenderEnum;
use App\Form\Type\MaskedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('gender', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'enum.gender.MALE' => GenderEnum::MALE,
                    'enum.gender.FEMALE' => GenderEnum::FEMALE,
                ],
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('phone', MaskedType::class, [
                'mask' => MaskedType::PHONE_MASK,
            ])
            ->add('email', EmailType::class)
            ->add('registrationInfo', RegistrationInfoType::class, [
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
            'label_format' => 'front.registration.form.%name%',
        ]);
    }
}
