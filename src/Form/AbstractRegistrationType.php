<?php

namespace App\Form;

use App\Entity\Payment\PriceOption;
use App\Entity\Purpose;
use App\Entity\Registration;
use App\Form\Type\BulmaFileType;
use App\Repository\Payment\PriceOptionRepository;
use App\Repository\PurposeRepository;
use LogicException;
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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

abstract class AbstractRegistrationType extends AbstractType
{
    public function __construct(protected RouterInterface $router)
    {
    }

    abstract protected function showPassSportHelp(): bool;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'required' => false,
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
            ->add('emergency', EmergencyType::class)
            ->add('withLegalRepresentative', CheckboxType::class, [
                'required' => false,
                'false_values' => [null, '0', 'false'],
            ])
        ;

        $builder->get('withLegalRepresentative')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                if (null === $form->getParent()) {
                    throw new LogicException('invalid parent');
                }

                $this->toggleLegalRepresentative($form->getParent(), true === $form->getData());
            }
        );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Registration $registration */
            $registration = $event->getData();
            $form = $event->getForm();

            $priceOptionOptions = [
                'class' => PriceOption::class,
                'choice_label' => function (PriceOption $priceOption) {
                    return sprintf('%s - %d€', $priceOption->getLabel(), $priceOption->getAmount());
                },
                'choices' => $registration->getSeason()->getPriceOptions()->toArray(),
            ];

            if ($registration->isReEnrollment()) {
                $priceOptionOptions['help'] = 'form.registration.priceOptionReEnrollmentHelp';
            }

            $form->add('priceOption', EntityType::class, $priceOptionOptions);

            $downloadPassCitizenUri = (null !== $registration->getId() && null !== $registration->getPassCitizenUrl())
                ? $this->router->generate('bo_download_pass_citizen', ['registration' => $registration->getId()])
                : null
            ;
            $downloadPassSportUri = (null !== $registration->getId() && null !== $registration->getPassSportUrl())
                ? $this->router->generate('bo_download_pass_sport', ['registration' => $registration->getId()])
                : null
            ;

            $this->processPassField($form, $registration->isUsePassCitizen(), 'usePassCitizen', 'passCitizenFile', $downloadPassCitizenUri);
            $this->processPassField($form, $registration->isUsePassSport(), 'usePassSport', 'passSportFile', $downloadPassSportUri);

            $this->toggleLegalRepresentative($form, $registration->isWithLegalRepresentative());
        });
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function addInternalFields(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('privateNote', TextareaType::class, [
                'required' => false,
            ])
            ->add('licenceNumber', TextType::class, [
                'required' => false,
            ])
            ->add('licenceDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('registeredAt', DateType::class, [
                'widget' => 'single_text',
            ])
        ;
    }

    protected function processPassField(FormInterface $form, bool $usePass, string $checkboxFieldName, string $uploadFieldName, ?string $downloadUri = null): void
    {
        $passOptions = [
            'required' => false,
            'false_values' => [null, '0', 'false'],
            'auto_initialize' => false,
        ];

        if ($this->showPassSportHelp()) {
            $passOptions['help'] = sprintf('form.registration.%sHelp', $checkboxFieldName);
            $passOptions['help_html'] = true;
        }

        $subBuilder = $form->getConfig()->getFormFactory()->createNamedBuilder($checkboxFieldName, CheckboxType::class, null, $passOptions);

        $subBuilder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($downloadUri, $uploadFieldName) {
                $form = $event->getForm();

                if (null === $form->getParent()) {
                    throw new LogicException('invalid parent');
                }

                $this->togglePass($form->getParent(), $uploadFieldName, true === $form->getData(), $downloadUri);
            }
        );

        $form->add($subBuilder->getForm());
        $this->togglePass($form, $uploadFieldName, $usePass, $downloadUri);
    }

    protected function toggleLegalRepresentative(FormInterface $form, bool $state): void
    {
        if (!$state) {
            $form->remove('legalRepresentative');

            return;
        }

        $form->add('legalRepresentative', LegalRepresentativeType::class);
    }

    protected function togglePass(FormInterface $form, string $fieldName, bool $state, ?string $downloadUri = null): void
    {
        if (!$state) {
            $form->remove($fieldName);

            return;
        }

        $options = [
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'image/gif',
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                        'application/pdf',
                    ],
                ]),
            ],
        ];

        if (null !== $downloadUri) {
            $options['download_uri'] = $downloadUri;
            $options['required'] = false;
        } else {
            $options['constraints'][] = new NotNull();
        }

        $form->add($fieldName, BulmaFileType::class, $options);
    }
}
