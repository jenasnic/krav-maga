<?php

namespace App\Validator\Constraint\Captcha;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class GoogleCaptcha extends Constraint
{
    public string $message = 'form.errors.invalid_captcha';
}
