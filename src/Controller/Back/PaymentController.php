<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\NewPaymentCommand;
use App\Domain\Command\Back\NewPaymentHandler;
use App\Entity\Adherent;
use App\Entity\Payment\AbstractPayment;
use App\Entity\Season;
use App\Enum\PaymentTypeEnum;
use App\Form\Payment\AncvPaymentType;
use App\Form\Payment\CashPaymentType;
use App\Form\Payment\CheckPaymentType;
use App\Form\Payment\NewPaymentType;
use App\Form\Payment\PassPaymentType;
use App\Form\Payment\TransferPaymentType;
use App\Helper\FloatHelper;
use App\Repository\Payment\PaymentRepository;
use App\Repository\RegistrationRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaymentController extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected SeasonRepository $seasonRepository,
        protected PaymentRepository $paymentRepository,
        protected RegistrationRepository $registrationRepository,
    ) {
    }

    #[Route('/paiements/{season}', name: 'bo_payment_list_for_season', methods: ['GET'])]
    public function listForSeason(?Season $season = null): Response
    {
        $season ??= $this->seasonRepository->getActiveSeason();

        if (null === $season) {
            $this->addFlash('warning', $this->translator->trans('back.season.activate.missingSeason'));

            return $this->redirectToRoute('bo_season_list');
        }

        /** @var int $seasonId */
        $seasonId = $season->getId();

        return $this->render('back/payment/list_for_season.html.twig', [
            'payments' => $this->paymentRepository->findForSeason($seasonId),
            'seasons' => $this->seasonRepository->search(),
            'currentSeason' => $season,
        ]);
    }

    #[Route('/adherent/{adherent}/paiements', name: 'bo_payment_list_for_adherent', methods: ['GET'])]
    public function listForAdherent(Adherent $adherent): Response
    {
        $season = $this->seasonRepository->getActiveSeason();

        if (null === $season) {
            $this->addFlash('warning', $this->translator->trans('back.season.activate.missingSeason'));

            return $this->redirectToRoute('bo_season_list');
        }

        /** @var int $adherentId */
        $adherentId = $adherent->getId();
        $registration = $this->registrationRepository->getForAdherent($adherentId);
        $payments = $this->paymentRepository->findForAdherent($adherentId);

        $amountToPay = 0;
        if ($registration->getSeason()->getId() === $season->getId()) {
            $totalPaidOnSeason = array_reduce($payments, function (float $amount, AbstractPayment $payment) use ($season) {
                if ($payment->getSeason()->getId() === $season->getId()) {
                    $amount += $payment->getAmount();
                }

                return $amount;
            }, 0);

            $amountToPay = $registration->getPriceOption()?->getAmount() - $totalPaidOnSeason;
        }

        return $this->render('back/payment/list_for_adherent.html.twig', [
            'payments' => $payments,
            'adherent' => $adherent,
            'registration' => $registration,
            'currentSeason' => $season,
            'amountToPay' => $amountToPay,
            'contributionSold' => FloatHelper::equals($amountToPay, 0),
        ]);
    }

    #[Route('/adherent/{adherent}/nouveau-paiement', name: 'bo_payment_new', methods: ['GET', 'POST', 'PATCH'])]
    public function add(Request $request, NewPaymentHandler $newPaymentHandler, Adherent $adherent): Response
    {
        $season = $this->seasonRepository->getActiveSeason();
        if (null === $season) {
            $this->addFlash('warning', $this->translator->trans('back.season.activate.missingSeason'));

            return $this->redirectToRoute('bo_payment_list_for_adherent', ['adherent' => $adherent->getId()]);
        }

        $newPayment = new NewPaymentCommand();

        $formOptions = [
            'adherent' => $adherent,
            'season' => $season,
        ];

        $isPatch = $request->isMethod(Request::METHOD_PATCH);
        if ($isPatch) {
            $formOptions['method'] = Request::METHOD_PATCH;
            $formOptions['validation_groups'] = false;
        }

        $form = $this->createForm(NewPaymentType::class, $newPayment, $formOptions);

        $form->handleRequest($request);

        if (!$isPatch && $form->isSubmitted() && $form->isValid()) {
            $newPaymentHandler->handle($newPayment);

            $this->addFlash('info', $this->translator->trans('back.payment.new.success'));

            return $this->redirectToRoute('bo_payment_list_for_adherent', ['adherent' => $adherent->getId()]);
        }

        return $this->render('back/payment/new.html.twig', [
            'form' => $form->createView(),
            'adherent' => $adherent,
        ]);
    }

    #[Route('/adherent/consulter-paiement/{payment}', name: 'bo_payment_view', methods: ['GET'])]
    public function viewForAdherent(AbstractPayment $payment): Response
    {
        return $this->view(
            $payment,
            $this->generateUrl('bo_payment_list_for_adherent', ['adherent' => $payment->getAdherent()->getId()]),
            $this->generateUrl('bo_payment_delete_for_adherent', ['payment' => $payment->getId()]),
        );
    }

    #[Route('/paiement/consulter/{payment}', name: 'bo_payment_view_for_season', methods: ['GET'])]
    public function viewForSeason(AbstractPayment $payment): Response
    {
        return $this->view(
            $payment,
            $this->generateUrl('bo_payment_list_for_season', ['season' => $payment->getSeason()->getId()]),
            $this->generateUrl('bo_payment_delete_for_season', ['payment' => $payment->getId()]),
        );
    }

    #[Route('/adherent/supprimer-paiement/{payment}', name: 'bo_payment_delete_for_adherent', methods: ['POST'])]
    public function deleteForAdherent(Request $request, AbstractPayment $payment): Response
    {
        return $this->delete($request, $payment, $this->generateUrl('bo_payment_list_for_adherent', ['adherent' => $payment->getAdherent()->getId()]));
    }

    #[Route('/paiement/supprimer/{payment}', name: 'bo_payment_delete_for_season', methods: ['POST'])]
    public function deleteForSeason(Request $request, AbstractPayment $payment): Response
    {
        return $this->delete($request, $payment, $this->generateUrl('bo_payment_list_for_season', ['season' => $payment->getSeason()->getId()]));
    }

    protected function view(AbstractPayment $payment, string $backLink, string $deleteLink): Response
    {
        $options = [
            'disabled' => true,
            'adherent' => $payment->getAdherent(),
            'season' => $payment->getSeason(),
        ];

        $form = match ($payment->getPaymentType()) {
            PaymentTypeEnum::ANCV => $this->createForm(AncvPaymentType::class, $payment, $options),
            PaymentTypeEnum::CASH => $this->createForm(CashPaymentType::class, $payment, $options),
            PaymentTypeEnum::CHECK => $this->createForm(CheckPaymentType::class, $payment, $options),
            PaymentTypeEnum::PASS => $this->createForm(PassPaymentType::class, $payment, $options),
            PaymentTypeEnum::TRANSFER => $this->createForm(TransferPaymentType::class, $payment, $options),
            default => throw new \LogicException('invalid payment type'),
        };

        return $this->render('back/payment/view.html.twig', [
            'form' => $form->createView(),
            'payment' => $payment,
            'backLink' => $backLink,
            'deleteLink' => $deleteLink,
        ]);
    }

    protected function delete(Request $request, AbstractPayment $payment, string $backLink): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$payment->getId(), (string) $request->request->get('_token'))) {
            $this->paymentRepository->remove($payment, true);

            $this->addFlash('info', $this->translator->trans('back.payment.delete.success'));
        }

        return $this->redirect($backLink, Response::HTTP_SEE_OTHER);
    }
}
