<?php

namespace App\Form\Type;

use App\ValueObject\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;
use TypeError;

class AddressType extends AbstractType implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', TextType::class)
            ->add('zipCode', MaskedType::class, [
                'mask' => MaskedType::ZIP_CODE_MASK,
            ])
            ->add('city', TextType::class)
            ->setDataMapper($this)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'empty_data' => null,
            'error_bubbling' => false,
            'validation_groups' => ['adherent'],
        ]);
    }

    public function mapDataToForms($viewData, Traversable $forms): void
    {
        if (null === $viewData) {
            return;
        }

        if (!$viewData instanceof Address) {
            throw new UnexpectedTypeException($viewData, Address::class);
        }

        $forms = iterator_to_array($forms);

        // initialize form field values
        $forms['street']->setData($viewData->getStreet());
        $forms['zipCode']->setData($viewData->getZipCode());
        $forms['city']->setData($viewData->getCity());
    }

    public function mapFormsToData(Traversable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);

        $allEmpty = empty(array_map(function (FormInterface $form) {
            return $form->getData();
        }, $forms));

        try {
            /** @var string|null $street */
            $street = $forms['street']->getData();
            /** @var string|null $zipCode */
            $zipCode = $forms['zipCode']->getData();
            /** @var string|null $city */
            $city = $forms['city']->getData();

            $viewData = new Address($street, $zipCode, $city);
        } catch (TypeError $error) {
            if ($allEmpty) {
                $viewData = null;
            } else {
                throw new TransformationFailedException();
            }
        }
    }
}
