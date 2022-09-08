<?php

namespace App\Form;

use App\Entity\Registration;
use App\Form\Type\BulmaFileType;
use App\Form\Type\GoogleCaptchaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;

class NewRegistrationType extends AbstractRegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        /** @var bool $forKmis */
        $forKmis = $options['kmis_version'];

        $builder
            ->add('medicalCertificateFile', BulmaFileType::class, [
                'required' => false,
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
                'help' => !$forKmis ? 'form.newRegistration.medicalCertificateFileHelp' : null,
                'help_html' => true,
            ])
            ->add('licenceFormFile', BulmaFileType::class, [
                'required' => false,
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
                'help' => !$forKmis ? 'form.newRegistration.licenceFormFileHelp' : null,
                'help_html' => true,
            ])
            ->add('adherent', AdherentType::class, [
                're_enrollment' => $options['re_enrollment'],
            ])
        ;

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
            'validation_groups' => ['adherent', 'registration'],
        ]);
    }

    protected function showPassSportHelp(): bool
    {
        return true;
    }
}
