<?php

namespace App\Validator\Constraint\Captcha;

use App\Service\Captcha\GoogleCaptchaValidation;
use LogicException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GoogleCaptchaValidator extends ConstraintValidator
{
    public function __construct(protected GoogleCaptchaValidation $googleCaptchaValidation)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof GoogleCaptcha) {
            throw new LogicException(sprintf('Constraint must be of type "%s".', GoogleCaptcha::class));
        }

        if (!$this->googleCaptchaValidation->isCaptchaValid()) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
