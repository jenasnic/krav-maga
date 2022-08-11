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
 * Allows to define a number input using mask input.
 *
 * @see https://imask.js.org/guide.html#masked-number
 */
class NumberType extends AbstractType
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        /** @var int $scale */
        $scale = $options['scale'];
        $builder->addViewTransformer(new MaskNumberTransformer($scale));
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['min'] = $options['min'];
        $view->vars['max'] = $options['max'];
        $view->vars['radix'] = $options['radix'];
        $view->vars['scale'] = $options['scale'];
        $view->vars['separator'] = $options['separator'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'min' => 0,
            'max' => 999999999,
            'radix' => ',',
            'scale' => 2,
            'separator' => ' ',
            'widget' => 'single_text',
        ]);
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
