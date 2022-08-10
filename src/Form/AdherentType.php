<?php

namespace App\Form;

use App\Entity\Adherent;
use App\Enum\GenderEnum;
use App\Form\Type\AddressType;
use App\Form\Type\BulmaFileType;
use App\Form\Type\MaskedType;
use Symfony\Component\Form\AbstractType;
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

class AdherentType extends AbstractType
{
    public function __construct(protected RouterInterface $router)
    {
    }

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
            ->add('pseudonym', TextType::class, [
                'required' => false,
                'help' => 'form.adherent.pseudonymHelp',
            ])
            ->add('address', AddressType::class, [
                'label' => false,
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var Adherent|null $adherent */
            $adherent = $event->getData();

            $constraints = [];
            $constraints[] = new File([
                'mimeTypes' => [
                    'image/gif',
                    'image/jpg',
                    'image/jpeg',
                    'image/png',
                ],
            ]);

            $options = [
                'constraints' => $constraints,
            ];

            if (null !== $adherent?->getPictureUrl()) {
                $options['download_uri'] = $this->router->generate('bo_download_picture', ['adherent' => $adherent->getId()]);
            }

            $form->add('pictureFile', BulmaFileType::class, $options);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
            'label_format' => 'form.adherent.%name%',
        ]);
    }
}
