<?php

namespace App\Service\Security;

use App\Service\Email\EmailSender;
use LogicException;
use Symfony\Component\HttpFoundation\RateLimiter\AbstractRequestRateLimiter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimit;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Security\Core\Security;

class LoginRateLimiter extends AbstractRequestRateLimiter
{
    public function __construct(
        protected RateLimiterFactory $loginLimiter,
        protected RateLimiterFactory $emailLimiter,
        protected EmailSender $emailSender,
        protected string $mailerContact,
    ) {
    }

    public function consume(Request $request): RateLimit
    {
        $loginRateLimit = parent::consume($request);

        if (!$loginRateLimit->isAccepted()) {
            $emailLimiter = $this->emailLimiter->create('login_throttling');
            $emailRateLimit = $emailLimiter->consume();

            if ($emailRateLimit->isAccepted()) {
                $this->emailSender->send(
                    'email/login_throttling.html.twig',
                    $this->mailerContact,
                    ['ip' => $request->getClientIp()],
                );
            }
        }

        return $loginRateLimit;
    }

    protected function getLimiters(Request $request): array
    {
        $username = $request->attributes->get(Security::LAST_USERNAME, '');
        if (!is_string($username)) {
            throw new LogicException('invalid username');
        }

        $username = preg_match('//u', $username) ? mb_strtolower($username, 'UTF-8') : strtolower($username);

        return [
            $this->loginLimiter->create($request->getClientIp()),
            $this->loginLimiter->create($username),
        ];
    }
}
