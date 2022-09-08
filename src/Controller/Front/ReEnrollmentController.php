<?php

namespace App\Controller\Front;

use App\Domain\Command\Front\ReEnrollmentCommand;
use App\Domain\Command\Front\ReEnrollmentHandler;
use App\Domain\Model\ReEnrollment;
use App\Entity\ReEnrollmentToken;
use App\Entity\Registration;
use App\Entity\Season;
use App\Enum\FileTypeEnum;
use App\Form\NewRegistrationType;
use App\Form\ReEnrollmentType;
use App\Repository\ReEnrollmentTokenRepository;
use App\Repository\RegistrationRepository;
use App\Repository\SeasonRepository;
use App\Service\File\FileCleaner;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReEnrollmentController extends AbstractController
{
    protected const RE_ENROLLMENT_TOKEN = 're_enrollment_token';

    public function __construct(
        protected RequestStack $requestStack,
        protected TranslatorInterface $translator,
        protected FileCleaner $fileCleaner,
        protected ReEnrollmentTokenRepository $reEnrollmentTokenRepository,
    ) {
    }

    #[Route('/reinscription/verification/{token}', name: 'app_re_enrollment', methods: ['GET', 'POST'])]
    public function reEnrollment(Request $request, string $token): Response
    {
        $reEnrollment = new ReEnrollment();
        $form = $this->createForm(ReEnrollmentType::class, $reEnrollment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reEnrollmentToken = $this->reEnrollmentTokenRepository->find($token);
            if (null === $reEnrollmentToken || $reEnrollmentToken->getAdherent()->getEmail() !== $reEnrollment->email) {
                $this->addFlash('error', $this->translator->trans('front.reEnrollment.error'));

                return $this->redirectToRoute('app_home');
            }

            $this->requestStack->getSession()->set(self::RE_ENROLLMENT_TOKEN, $token);

            return $this->redirectToRoute('app_re_enrollement_update');
        }

        return $this->render('front/re_enrollment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reinscription/mise-a-jour', name: 'app_re_enrollement_update')]
    public function reEnrollmentUpdate(
        Request $request,
        RegistrationRepository $registrationRepository,
        SeasonRepository $seasonRepository,
        ReEnrollmentHandler $reEnrollmentHandler,
    ): Response {
        $reEnrollmentToken = $this->getReEnrollmentToken();

        if ($reEnrollmentToken->getExpiresAt() < new DateTime()) {
            $this->addFlash('error', $this->translator->trans('front.reEnrollment.expired'));

            return $this->redirectToRoute('app_home');
        }

        /** @var int $adherentId */
        $adherentId = $reEnrollmentToken->getAdherent()->getId();
        $registration = $registrationRepository->getForAdherent($adherentId);

        $season = $seasonRepository->getActiveSeason();
        if (null === $season) {
            $this->addFlash('error', $this->translator->trans('front.reEnrollment.expired'));

            return $this->redirectToRoute('app_home');
        }

        $this->prepareRegistrationForReEnrollment($registration, $season);

        $formOptions = ['re_enrollment' => true];

        $isPatch = $request->isMethod(Request::METHOD_PATCH);
        if ($isPatch) {
            $formOptions['method'] = Request::METHOD_PATCH;
            $formOptions['validation_groups'] = false;
        }

        $form = $this->createForm(NewRegistrationType::class, $registration, $formOptions);
        $form->handleRequest($request);

        if (!$isPatch && $form->isSubmitted() && $form->isValid()) {
            $reEnrollmentHandler->handle(new ReEnrollmentCommand($registration, $reEnrollmentToken));

            $this->requestStack->getSession()->remove(self::RE_ENROLLMENT_TOKEN);

            $this->addFlash('info', $this->translator->trans('front.reEnrollment.success'));

            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    protected function getReEnrollmentToken(): ReEnrollmentToken
    {
        $token = $this->requestStack->getSession()->get(self::RE_ENROLLMENT_TOKEN);

        if (null === $token) {
            throw $this->createNotFoundException('No re-enrollment token found.');
        }

        $reEnrollmentToken = $this->reEnrollmentTokenRepository->find($token);

        if (null === $reEnrollmentToken) {
            throw $this->createNotFoundException('No re-enrollment token found.');
        }

        return $reEnrollmentToken;
    }

    protected function prepareRegistrationForReEnrollment(Registration $registration, Season $season): void
    {
        $registration->setSeason($season);
        $registration->setCopyrightAuthorization(null);
        $registration->setUsePass15(false);
        $registration->setUsePass50(false);
        $registration->setLicenceDate(null);
        $registration->setPriceOption(null);

        // remove useless attached files (should be renewed)
        $this->fileCleaner->cleanEntity($registration, FileTypeEnum::MEDICAL_CERTIFICATE);
        $this->fileCleaner->cleanEntity($registration, FileTypeEnum::LICENCE_FORM);
        $this->fileCleaner->cleanEntity($registration, FileTypeEnum::PASS_15);
        $this->fileCleaner->cleanEntity($registration, FileTypeEnum::PASS_50);
    }
}
