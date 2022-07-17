<?php

namespace App\Controller\Front;

use App\Domain\Command\Front\ConfirmRegistrationCommand;
use App\Domain\Command\Front\ConfirmRegistrationHandler;
use App\Domain\Command\Front\RegistrationCommand;
use App\Domain\Command\Front\RegistrationHandler;
use App\Entity\Member;
use App\Form\MemberType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
    ) {
    }

    #[Route('/inscription', name: 'app_registration')]
    public function registration(Request $request, RegistrationHandler $registrationHandler): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationHandler->handle(new RegistrationCommand($member));

            // @todo : add flash message to inform user email has been sent (for validation...)

            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/valider-une-inscription/{member}', name: 'app_confirm_registration')]
    public function confirmRegistration(
        Request $request,
        ConfirmRegistrationHandler $confirmRegistrationHandler,
        Member $member,
    ): Response {
        if (null !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        try {
            $confirmRegistrationHandler->handle(new ConfirmRegistrationCommand($member, $request));
        } catch (HandlerFailedException $exception) {
            $previous = $exception->getPrevious();
            if ($previous instanceof VerifyEmailExceptionInterface) {
                $this->addFlash('error', $this->translator->trans($previous->getReason(), [], 'VerifyEmailBundle'));

                return $this->redirectToRoute('app_registration');
            } else {
                throw $exception;
            }
        }

        return $this->redirectToRoute('app_home');
    }
}
