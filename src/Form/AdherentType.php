<?php

namespace App\Form;

use App\Entity\Adherent;
use App\Enum\GenderEnum;
use App\Form\Type\AddressType;
use App\Form\Type\BulmaFileType;
use App\Form\Type\MaskedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class AdherentType extends AbstractType
{
    public function __construct(protected RouterInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'disabled' => $options['re_enrollment'],
            ])
            ->add('lastName', TextType::class, [
                'disabled' => $options['re_enrollment'],
            ])
            ->add('gender', ChoiceType::class, [
                'disabled' => $options['re_enrollment'],
                'expanded' => true,
                'choices' => [
                    'enum.gender.MALE' => GenderEnum::MALE,
                    'enum.gender.FEMALE' => GenderEnum::FEMALE,
                ],
            ])
            ->add('birthDate', DateType::class, [
                'disabled' => $options['re_enrollment'],
                'widget' => 'single_text',
            ])
            ->add('phone', MaskedType::class, [
                'mask' => MaskedType::PHONE_MASK,
            ])
            ->add('email', EmailType::class)
            ->add('pseudonym', TextType::class, [
                'required' => false,
                'help' => 'form.adherent.pseudonymHelp',
                'help_html' => true,
            ])
            ->add('address', AddressType::class, [
                'label' => false,
            ])
        ;

        if ($options['manage_re_enrollment_notification']) {
            $builder->add('reEnrollmentToNotify', CheckboxType::class, [
                'required' => false,
                'help' => 'form.adherent.reEnrollmentToNotifyHelp',
            ]);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $form = $event->getForm();
            /** @var Adherent|null $adherent */
            $adherent = $event->getData();

            /** @var bool $forKmis */
            $forKmis = $options['kmis_version'];

            $fieldOptions = [
                'required' => !$forKmis,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                    ]),
                ],
            ];

            if (null !== $adherent?->getPictureUrl()) {
                $fieldOptions['required'] = false;

                if ($options['re_enrollment']) {
                    $fieldOptions['help'] = 'form.adherent.pictureFileHelp';
                } else {
                    $fieldOptions['download_uri'] = $this->router->generate('bo_download_picture', ['adherent' => $adherent->getId()]);
                }
            } elseif (!$forKmis) {
                $fieldOptions['constraints'][] = new NotNull();
            }

            $form->add('pictureFile', BulmaFileType::class, $fieldOptions);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['kmis_version', 're_enrollment']);
        $resolver->setAllowedTypes('kmis_version', 'bool');
        $resolver->setAllowedTypes('re_enrollment', 'bool');

        $resolver->setDefined(['kmis_version', 're_enrollment', 'manage_re_enrollment_notification']);
        $resolver->setAllowedTypes('kmis_version', 'bool');
        $resolver->setAllowedTypes('re_enrollment', 'bool');
        $resolver->setAllowedTypes('manage_re_enrollment_notification', 'bool');

        $resolver->setDefaults([
            'data_class' => Adherent::class,
            'label_format' => 'form.adherent.%name%',
            're_enrollment' => false,
            'manage_re_enrollment_notification' => false,
        ]);
    }
}
