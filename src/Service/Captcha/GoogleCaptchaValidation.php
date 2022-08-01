<?php

namespace App\Service\Captcha;

use LogicException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GoogleCaptchaValidation
{
    public function __construct(
        protected RequestStack $requestStack,
        protected HttpClientInterface $googleCaptchaClient,
        protected string $googleCaptchaPrivateKey,
    ) {
    }

    public function isCaptchaValid(): bool
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (null === $currentRequest) {
            throw new LogicException('Current request can\'t be empty.');
        }

        $response = $this->googleCaptchaClient->request('POST', 'siteverify', [
            'body' => [
                'secret' => $this->googleCaptchaPrivateKey,
                'response' => $currentRequest->request->get('g-recaptcha-response'),
            ],
        ]);

        /** @var array{success: bool} $content */
        $content = json_decode($response->getContent(), true);

        return $content['success'];
    }
}
