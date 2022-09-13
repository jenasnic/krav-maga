<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\NewRegistrationCommand;
use App\Domain\Command\Back\NewRegistrationHandler;
use App\Domain\Command\Back\SaveRegistrationCommand;
use App\Domain\Command\Back\SaveRegistrationHandler;
use App\Entity\Registration;
use App\Exception\NoActiveSeasonException;
use App\Form\NewRegistrationType;
use App\Form\RegistrationType;
use App\Repository\RegistrationRepository;
use App\Service\Factory\RegistrationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(protected TranslatorInterface $translator)
    {
    }

    #[Route('/adherent/nouvelle-inscription', name: 'bo_registration_new', methods: ['GET', 'POST', 'PATCH'])]
    public function add(
        Request $request,
        RegistrationFactory $registrationFactory,
        NewRegistrationHandler $newRegistrationHandler,
    ): Response {
        try {
            $registration = $registrationFactory->createNew();
        } catch (NoActiveSeasonException $exception) {
            $this->addFlash('warning', $this->translator->trans('back.season.activate.missingSeason'));

            return $this->redirectToRoute('bo_adherent_list');
        }

        $formOptions = ['kmis_version' => true];

        $isPatch = $request->isMethod(Request::METHOD_PATCH);
        if ($isPatch) {
            $formOptions['method'] = Request::METHOD_PATCH;
            $formOptions['validation_groups'] = false;
        }

        $form = $this->createForm(NewRegistrationType::class, $registration, $formOptions);
        $form->handleRequest($request);

        if (!$isPatch && $form->isSubmitted() && $form->isValid()) {
            $newRegistrationHandler->handle(new NewRegistrationCommand($registration));

            $this->addFlash('info', $this->translator->trans('back.registration.new.success'));

            return $this->redirectToRoute('bo_adherent_list');
        }

        return $this->render('back/registration/new.html.twig', [
            'form' => $form->createView(),
            'registration' => $registration,
        ]);
    }

    #[Route('/adherent/fiche-inscription/{registration}', name: 'bo_registration_edit', methods: ['GET', 'POST', 'PATCH'])]
    public function edit(Request $request, SaveRegistrationHandler $saveRegistrationHandler, Registration $registration): Response
    {
        $formOptions = [];

        $isPatch = $request->isMethod(Request::METHOD_PATCH);
        if ($isPatch) {
            $formOptions['method'] = Request::METHOD_PATCH;
            $formOptions['validation_groups'] = false;
        }

        $form = $this->createForm(RegistrationType::class, $registration, $formOptions);
        $form->handleRequest($request);

        if (!$isPatch && $form->isSubmitted() && $form->isValid()) {
            $saveRegistrationHandler->handle(new SaveRegistrationCommand($registration));

            $this->addFlash('info', $this->translator->trans('back.registration.edit.success'));

            return $this->redirectToRoute('bo_adherent_list');
        }

        return $this->render('back/registration/edit.html.twig', [
             'form' => $form->createView(),
             'registration' => $registration,
         ]);
    }

    #[Route('/adherent/verifier-inscription/{registration}', name: 'bo_registration_verify', methods: ['POST'])]
    public function delete(Request $request, RegistrationRepository $registrationRepository, Registration $registration): Response
    {
        if ($this->isCsrfTokenValid('verify-'.$registration->getId(), (string) $request->request->get('_token'))) {
            $registration->setVerified(true);
            $registrationRepository->add($registration, true);

            $this->addFlash('info', $this->translator->trans('back.registration.verify.success'));
        }

        return $this->redirectToRoute('bo_registration_edit', ['registration' => $registration->getId()], Response::HTTP_SEE_OTHER);
    }
}
