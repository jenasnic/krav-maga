<?php

namespace App\Form;

use App\Entity\Registration;
use App\Form\Type\BulmaFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegistrationType extends AbstractRegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addInternalFields($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var Registration $registration */
            $registration = $event->getData();

            $medicalCertificateOptions = $licenceFormOptions = [
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
            ];

            if (null !== $registration->getMedicalCertificateUrl()) {
                $medicalCertificateOptions['download_uri'] = $this->router->generate('bo_download_attestation', ['registration' => $registration->getId()]);
            }

            if (null !== $registration->getLicenceFormUrl()) {
                $licenceFormOptions['download_uri'] = $this->router->generate('bo_download_licence_form', ['registration' => $registration->getId()]);
            }

            $form->add('medicalCertificateFile', BulmaFileType::class, $medicalCertificateOptions);
            $form->add('licenceFormFile', BulmaFileType::class, $licenceFormOptions);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
            'label_format' => 'form.registration.%name%',
            'validation_groups' => ['registration'],
        ]);
    }

    protected function showPassSportHelp(): bool
    {
        return false;
    }
}
