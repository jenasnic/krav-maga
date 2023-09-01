<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WysiwygType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['small_size'] = $options['small_size'] ?? false;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('small_size');
        $resolver->setAllowedTypes('small_size', 'bool');

        $resolver->setDefault('required', false);
        $resolver->setDefault('help', 'form.wysiwyg.help');
    }

    public function getParent()
    {
        return TextareaType::class;
    }
}
