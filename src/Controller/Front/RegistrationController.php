<?php

namespace App\Controller\Front;

use App\Domain\Command\RegistrationCommand;
use App\Domain\Command\RegistrationHandler;
use App\Entity\Member;
use App\Form\MemberType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_registration')]
    public function registration(Request $request, RegistrationHandler $registrationHandler): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationHandler->handle(new RegistrationCommand($member));

            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
