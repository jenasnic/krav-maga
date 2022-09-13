<?php

namespace App\Form\Type;

use App\Validator\Constraint\Captcha\GoogleCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoogleCaptchaType extends AbstractType
{
    public function __construct(protected string $googleCaptchaSiteKey)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['site_key'] = $this->googleCaptchaSiteKey;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => false,
            'constraints' => [
                new GoogleCaptcha(),
            ],
            'error_bubbling' => false,
        ]);
    }
}
