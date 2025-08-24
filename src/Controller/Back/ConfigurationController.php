<?php

namespace App\Controller\Back;

use App\Service\Configuration\ConfigurationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigurationController extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected ConfigurationManager $configurationManager,
    ) {
    }

    #[Route('/configuration/envois-automatiques/activer', name: 'bo_configuration_automatic_send_enable', methods: ['POST'])]
    public function enableAutomaticSend(Request $request): Response
    {
        if ($this->isCsrfTokenValid('enable_automatic_send_token', (string) $request->request->get('_token'))) {
            $this->configurationManager->enableAutomaticSend();

            $this->addFlash('info', $this->translator->trans('back.registration.automaticSend.enableMessage'));
        }

        return $this->redirectToRoute('bo_season_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/configuration/envois-automatiques/desactiver', name: 'bo_configuration_automatic_send_disable', methods: ['POST'])]
    public function disableAutomaticSend(Request $request): Response
    {
        if ($this->isCsrfTokenValid('disable_automatic_send_token', (string) $request->request->get('_token'))) {
            $this->configurationManager->disableAutomaticSend();

            $this->addFlash('info', $this->translator->trans('back.registration.automaticSend.disableMessage'));
        }

        return $this->redirectToRoute('bo_season_list', [], Response::HTTP_SEE_OTHER);
    }
}
