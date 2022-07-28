<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\FileType as BaseFileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Allows to define a field with pattern mask input.
 *
 * @see https://imask.js.org/guide.html#masked-pattern
 */
class FileType extends BaseFileType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);

        if (isset($options['download_uri'])) {
            $view->vars['download_uri'] = $options['download_uri'];
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined('download_uri');
        $resolver->setAllowedTypes('download_uri', 'string');
    }
}
