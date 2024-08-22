<?php

namespace App\Form;

use App\Entity\Registration;
use App\Enum\RegistrationTypeEnum;
use App\Form\Type\BulmaFileType;
use App\Form\Type\GoogleCaptchaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotNull;

class NewRegistrationType extends AbstractRegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        /** @var bool $forKmis */
        $forKmis = $options['kmis_version'];

        $fileConstraints = [
            new File([
                'mimeTypes' => [
                    'image/gif',
                    'image/jpg',
                    'image/jpeg',
                    'image/png',
                    'application/pdf',
                ],
            ]),
        ];

        if (!$forKmis) {
            $fileConstraints[] = new NotNull();
        }

        $builder
            ->add('licenceFormFile', BulmaFileType::class, [
                'required' => !$forKmis,
                'constraints' => $fileConstraints,
                'help' => !$forKmis ? 'form.newRegistration.licenceFormFileHelp' : null,
                'help_html' => true,
            ])
            ->add('adherent', AdherentType::class, [
                're_enrollment' => $options['re_enrollment'],
                'kmis_version' => $options['kmis_version'],
            ])
        ;

        if ($forKmis) {
            $builder->add('medicalCertificateFile', BulmaFileType::class, [
                'required' => false,
                'constraints' => $fileConstraints,
            ]);
        } else {
            $builder->get('registrationType')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($forKmis) {
                    $form = $event->getForm();

                    if (null === $form->getParent()) {
                        throw new \LogicException('invalid parent');
                    }

                    /** @var string $registrationType */
                    $registrationType = $form->getData();
                    if (!in_array($registrationType, [RegistrationTypeEnum::COMPETITOR, RegistrationTypeEnum::MINOR])) {
                        return;
                    }

                    $this->toggleMedicalCertificate($form->getParent(), $registrationType, $forKmis);
                }
            );
        }

        if ($options['kmis_version']) {
            $this->addInternalFields($builder, $options);
        } else {
            $builder
                ->add('captcha', GoogleCaptchaType::class)
                ->add('agreement', CheckboxType::class, [
                    'mapped' => false,
                    'label_html' => true,
                    'constraints' => [
                        new IsTrue(null, 'form.errors.check_required', ['registration']),
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['kmis_version', 're_enrollment']);
        $resolver->setAllowedTypes('kmis_version', 'bool');
        $resolver->setAllowedTypes('re_enrollment', 'bool');

        $resolver->setDefaults([
            'data_class' => Registration::class,
            'label_format' => 'form.newRegistration.%name%',
            'kmis_version' => false,
            're_enrollment' => false,
        ]);
    }

    protected function showPassSportHelp(): bool
    {
        return true;
    }

    protected function toggleMedicalCertificate(FormInterface $form, string $registrationType, bool $forKmis): void
    {
        $fileConstraints = [
            new File([
                'mimeTypes' => [
                    'image/gif',
                    'image/jpg',
                    'image/jpeg',
                    'image/png',
                    'application/pdf',
                ],
            ]),
        ];

        if (!$forKmis) {
            $fileConstraints[] = new NotNull();
        }

        $isForMinor = RegistrationTypeEnum::MINOR === $registrationType;

        $form->add('medicalCertificateFile', BulmaFileType::class, [
            'label' => $isForMinor ? 'form.newRegistration.medicalCertificateFile.forMinor' : 'form.newRegistration.medicalCertificateFile.default',
            'required' => !$forKmis,
            'constraints' => $fileConstraints,
            'help' => (!$forKmis && $isForMinor) ? 'form.newRegistration.medicalCertificateFileHelp' : null,
            'help_html' => true,
        ]);
    }
}
