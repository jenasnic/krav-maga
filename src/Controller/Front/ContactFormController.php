<?php

namespace App\Controller\Front;

use App\Domain\Command\Front\ContactFormCommand;
use App\Domain\Command\Front\ContactFormHandler;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactFormController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request,
        TranslatorInterface $translator,
        ContactFormHandler $contactFormHandler
    ): Response {
        $contactForm = new ContactFormCommand();
        $form = $this->createForm(ContactFormType::class, $contactForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormHandler->handle($contactForm);

            $this->addFlash('info', $translator->trans('form.contact.success'));

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('front/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
