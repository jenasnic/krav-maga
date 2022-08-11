<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\NewPaymentCommand;
use App\Domain\Command\Back\NewPaymentHandler;
use App\Entity\Adherent;
use App\Entity\Payment\AbstractPayment;
use App\Enum\PaymentTypeEnum;
use App\Form\Payment\AncvPaymentType;
use App\Form\Payment\CashPaymentType;
use App\Form\Payment\CheckPaymentType;
use App\Form\Payment\NewPaymentType;
use App\Form\Payment\PassPaymentType;
use App\Form\Payment\TransferPaymentType;
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

    #[Route('/adherent/{adherent}/paiements', name: 'bo_payment_list', methods: ['GET'])]
    public function list(Adherent $adherent): Response
    {
        /** @var int $adherentId */
        $adherentId = $adherent->getId();

        return $this->render('back/payment/list.html.twig', [
            'payments' => $this->paymentRepository->findForAdherent($adherentId),
            'adherent' => $adherent,
            'registration' => $this->registrationRepository->getForAdherent($adherentId),
        ]);
    }

    #[Route('/adherent/{adherent}/nouveau-paiement', name: 'bo_payment_new', methods: ['GET', 'POST', 'PATCH'])]
    public function add(Request $request, NewPaymentHandler $newPaymentHandler, Adherent $adherent): Response
    {
        $season = $this->seasonRepository->getActiveSeason();
        if (null === $season) {
            $this->addFlash('warning', $this->translator->trans('back.payment.new.missingSeason'));

            return $this->redirectToRoute('bo_payment_list', ['adherent' => $adherent->getId()]);
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

            return $this->redirectToRoute('bo_payment_list', ['adherent' => $adherent->getId()]);
        }

        return $this->render('back/payment/new.html.twig', [
            'form' => $form->createView(),
            'adherent' => $adherent,
        ]);
    }

    #[Route('/adherent/consulter-paiement/{payment}', name: 'bo_payment_view', methods: ['GET'])]
    public function view(AbstractPayment $payment): Response
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
        ]);
    }

    #[Route('/adherent/supprimer-paiement/{payment}', name: 'bo_payment_delete', methods: ['POST'])]
    public function delete(Request $request, AbstractPayment $payment): Response
    {
        $adherentId = $payment->getAdherent()->getId();

        if ($this->isCsrfTokenValid('delete-'.$payment->getId(), (string) $request->request->get('_token'))) {
            $this->paymentRepository->remove($payment, true);

            $this->addFlash('info', $this->translator->trans('back.payment.delete.success'));
        }

        return $this->redirectToRoute('bo_payment_list', ['adherent' => $adherentId], Response::HTTP_SEE_OTHER);
    }
}
