<?php

namespace App\Controller\Back;

use App\Domain\Command\Front\ReEnrollmentCommand;
use App\Domain\Command\Front\ReEnrollmentHandler;
use App\Entity\Registration;
use App\Enum\FileTypeEnum;
use App\Form\RegistrationType;
use App\Repository\SeasonRepository;
use App\Service\File\FileCleaner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReEnrollmentController extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected SeasonRepository $seasonRepository,
        protected ReEnrollmentHandler $reEnrollmentHandler,
        protected FileCleaner $fileCleaner,
    ) {
    }

    #[Route('/adherent/re-inscription/{registration}', name: 'bo_re_enrollment', methods: ['GET', 'POST', 'PATCH'])]
    public function reEnrollment(
        Request $request,
        Registration $registration,
    ): Response {

        $season = $this->seasonRepository->getActiveSeason();
        if (null === $season || $season->getId() === $registration->getSeason()->getId()) {
            $this->addFlash('error', $this->translator->trans('back.registration.reEnrollment.error'));

            return $this->redirectToRoute('bo_registration_edit', ['registration' => $registration->getId()]);
        }

        $formOptions = [];

        $isPatch = $request->isMethod(Request::METHOD_PATCH);
        if ($isPatch) {
            $formOptions['method'] = Request::METHOD_PATCH;
            $formOptions['validation_groups'] = false;
        }

        $registration->prepareForReEnrollment($season);
        $this->removeRegistrationFilesForReEnrollment($registration);

        $form = $this->createForm(RegistrationType::class, $registration, $formOptions);
        $form->handleRequest($request);

        if (!$isPatch && $form->isSubmitted() && $form->isValid()) {
            $this->reEnrollmentHandler->handle(new ReEnrollmentCommand($registration));

            $this->addFlash('info', $this->translator->trans('back.registration.reEnrollment.success'));

            return $this->redirectToRoute('bo_adherent_list');
        }

        return $this->render('back/registration/edit.html.twig', [
            'form' => $form->createView(),
            'registration' => $registration,
            'reEnrollment' => true,
        ]);
    }

    protected function removeRegistrationFilesForReEnrollment(Registration $registration): void
    {
        // remove useless attached files (should be renewed)
        $this->fileCleaner->cleanEntity($registration, FileTypeEnum::MEDICAL_CERTIFICATE);
        $this->fileCleaner->cleanEntity($registration, FileTypeEnum::LICENCE_FORM);
        $this->fileCleaner->cleanEntity($registration, FileTypeEnum::PASS_CITIZEN);
        $this->fileCleaner->cleanEntity($registration, FileTypeEnum::PASS_SPORT);
    }
}
