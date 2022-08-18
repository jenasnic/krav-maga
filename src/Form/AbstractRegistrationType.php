<?php

namespace App\Form;

use App\Entity\Payment\PriceOption;
use App\Entity\Purpose;
use App\Entity\Registration;
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

abstract class AbstractRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'required' => false,
            ])
            ->add('ffkPassport', CheckboxType::class, [
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

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            /** @var Registration $data */
            $data = $event->getData();

            $subBuilder = $form->getConfig()->getFormFactory()->createNamedBuilder('withLegalRepresentative', CheckboxType::class, null, [
                'required' => false,
                'data' => $data->isWithLegalRepresentative(),
                'auto_initialize' => false,
                'false_values' => [null, '0', 'false'],
            ]);

            $subBuilder->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();

                    if (null === $form->getParent()) {
                        throw new LogicException('invalid parent');
                    }

                    $this->toggleLegalRepresentative($form->getParent(), (true === $form->getData()));
                }
            );

            $form->add($subBuilder->getForm());

            $this->toggleLegalRepresentative($form, $data->isWithLegalRepresentative());
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
            ->add('licenseNumber', TextType::class)
            ->add('licenseDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('registeredAt', DateType::class, [
                'widget' => 'single_text',
            ])
        ;
    }

    protected function toggleLegalRepresentative(FormInterface $form, bool $state): void
    {
        if (!$state) {
            $form->remove('legalRepresentative');

            return;
        }

        $form->add('legalRepresentative', LegalRepresentativeType::class);
    }
}
