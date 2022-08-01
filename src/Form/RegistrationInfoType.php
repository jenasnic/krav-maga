<?php

namespace App\Form;

use App\Entity\Purpose;
use App\Entity\RegistrationInfo;
use App\Form\Type\FileType;
use App\Repository\PurposeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationInfoType extends AbstractType
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
            ->add('contact', ContactType::class)
            ->add('ffkPassport', CheckboxType::class, [
                'required' => false,
            ])
            ->add('purpose', EntityType::class, [
                'class' => Purpose::class,
                'choice_label' => 'label',
                'query_builder' => function (PurposeRepository $purposeRepository) {
                    return $purposeRepository->createQueryBuilder('purpose')->orderBy('purpose.rank');
                },
            ])
            ->add('copyrightAuthorization', ChoiceType::class, [
                'choices' => [
                    'global.yes' => true,
                    'global.no' => false,
                ],
                'expanded' => true,
                'required' => true,
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var RegistrationInfo|null $registrationInfo */
            $registrationInfo = $event->getData();

            $isRequired = (null === $registrationInfo) || (null === $registrationInfo->getMedicalCertificateUrl());

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
                'help' => 'front.registration.form.medicalCertificateFileHelp',
                'help_html' => true,
            ];

            if (null !== $registrationInfo?->getMedicalCertificateUrl()) {
                $options['download_uri'] = $this->router->generate('bo_download_attestation', ['registrationInfo' => $registrationInfo->getId()]);
            }

            $form->add('medicalCertificateFile', FileType::class, $options);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationInfo::class,
        ]);
    }
}
