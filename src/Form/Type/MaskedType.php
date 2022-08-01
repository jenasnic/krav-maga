<?php

namespace App\Form\Type;

use App\Form\DataTransformer\MaskNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Allows to define a field with pattern mask input.
 *
 * @see https://imask.js.org/guide.html#masked-pattern
 */
class MaskedType extends AbstractType
{
    public const PHONE_MASK = '00 00 00 00 00';
    public const ZIP_CODE_MASK = '00 000';

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        if ($options['is_numeric']) {
            $builder->addViewTransformer(new MaskNumberTransformer(2));
        }
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['mask'] = $options['mask'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('mask');

        $resolver->setDefaults([
            'is_numeric' => false,
            'widget' => 'single_text',
        ]);
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
