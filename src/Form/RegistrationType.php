<?php

namespace App\Form;

use App\Entity\Registration;
use App\Form\Type\BulmaFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationType extends AbstractRegistrationType
{
    public function __construct(protected RouterInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addInternalFields($builder, $options);

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
        $resolver->setDefaults([
            'data_class' => Registration::class,
            'label_format' => 'form.registration.%name%',
        ]);
    }
}
