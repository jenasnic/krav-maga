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
                'help' => 'form.newRegistration.medicalCertificateFileHelp',
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
                'help' => 'form.newRegistration.licenceFormFileHelp',
                'help_html' => true,
            ])
            ->add('adherent', AdherentType::class)
            ->add('agreement', CheckboxType::class, [
                'mapped' => false,
                'label_html' => true,
                'constraints' => [
                    new IsTrue(null, 'form.errors.check_required', ['registration']),
                ],
            ])
        ;

        if ($options['full_form']) {
            $this->addInternalFields($builder, $options);
        }

        if ($options['with_captcha']) {
            $builder->add('captcha', GoogleCaptchaType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['full_form', 'with_captcha']);
        $resolver->setAllowedTypes('full_form', 'bool');
        $resolver->setAllowedTypes('with_captcha', 'bool');

        $resolver->setDefaults([
            'data_class' => Registration::class,
            'label_format' => 'form.newRegistration.%name%',
            'full_form' => false,
            'with_captcha' => false,
            'validation_groups' => ['adherent', 'registration'],
        ]);
    }
}
