<?php

namespace App\Controller\Front;

use App\Domain\Command\Front\ContactFormCommand;
use App\Domain\Command\Front\ContactFormHandler;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactFormController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, ContactFormHandler $contactFormHandler): Response
    {
        $contactForm = new ContactFormCommand();
        $form = $this->createForm(ContactFormType::class, $contactForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormHandler->handle($contactForm);

            // @todo : add flash message to inform user email has been sent

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('front/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
