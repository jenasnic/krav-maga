<?php

namespace App\Form;

use App\Entity\Payment\PriceOption;
use App\Entity\Purpose;
use App\Entity\Registration;
use App\Form\Type\BulmaFileType;
use App\Repository\Payment\PriceOptionRepository;
use App\Repository\PurposeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationType extends AbstractType
{
    public function __construct(protected RouterInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'required' => false,
            ])
            ->add('privateNote', TextareaType::class, [
                'required' => false,
            ])
            ->add('licenseNumber', TextType::class)
            ->add('licenseDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('ffkPassport', CheckboxType::class, [
                'required' => false,
            ])
            ->add('registeredAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('copyrightAuthorization', ChoiceType::class, [
                'choices' => [
                    'global.yes' => true,
                    'global.no' => false,
                ],
                'expanded' => true,
                'required' => true,
            ])
            ->add('purpose', EntityType::class, [
                'class' => Purpose::class,
                'choice_label' => 'label',
                'query_builder' => function (PurposeRepository $purposeRepository) {
                    return $purposeRepository->createQueryBuilder('purpose')->orderBy('purpose.rank');
                },
            ])
            ->add('priceOption', EntityType::class, [
                'class' => PriceOption::class,
                'choice_label' => function (PriceOption $priceOption) {
                    return sprintf('%s - %d€', $priceOption->getLabel(), $priceOption->getAmount());
                },
                'query_builder' => function (PriceOptionRepository $priceOptionRepository) {
                    return $priceOptionRepository->createQueryBuilder('price_option')->orderBy('price_option.rank');
                },
            ])
            ->add('emergency', EmergencyType::class)
        ;

        if ($options['full_form']) {
            $builder->add('adherent', AdherentType::class);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var Registration|null $registration */
            $registration = $event->getData();

            $isRequired = (null === $registration) || (null === $registration->getMedicalCertificateUrl());

            $constraints = [];
            $constraints[] = new File([
                'mimeTypes' => [
                    'image/gif',
                    'image/jpg',
                    'image/jpeg',
                    'image/png',
                    'application/pdf',
                ],
            ]);

            if ($isRequired) {
                $constraints[] = new NotNull();
            }

            $options = [
                'required' => $isRequired,
                'constraints' => $constraints,
            ];

            if (null !== $registration?->getMedicalCertificateUrl()) {
                $options['download_uri'] = $this->router->generate('bo_download_attestation', ['registration' => $registration->getId()]);
            }

            $form->add('medicalCertificateFile', BulmaFileType::class, $options);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('full_form');
        $resolver->setAllowedTypes('full_form', 'bool');

        $resolver->setDefaults([
            'data_class' => Registration::class,
            'label_format' => 'form.registration.%name%',
            'full_form' => false,
        ]);
    }
}
