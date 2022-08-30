<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\ActivateSeasonCommand;
use App\Domain\Command\Back\ActivateSeasonHandler;
use App\Entity\Season;
use App\Exception\SeasonAlreadyDefinedException;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Service\Factory\SeasonFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SeasonController extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected SeasonRepository $seasonRepository,
    ) {
    }

    #[Route('/saison-sportive/liste', name: 'bo_season_list', methods: ['GET'])]
    public function list(): Response
    {
        return $this->render('back/season/list.html.twig', [
            'seasons' => $this->seasonRepository->search(),
        ]);
    }

    #[Route('/saison-sportive/nouvelle', name: 'bo_season_new', methods: ['GET', 'POST'])]
    public function add(Request $request, SeasonFactory $seasonFactory): Response
    {
        try {
            $season = $seasonFactory->createNew();

            return $this->edit($request, $season);
        } catch (SeasonAlreadyDefinedException $e) {
            $this->addFlash('danger', $this->translator->trans('back.season.new.alreadyExist'));

            return $this->redirectToRoute('bo_season_list');
        }
    }

    #[Route('/saison-sportive/modifier/{season}', name: 'bo_season_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Season $season): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->seasonRepository->add($season, true);

            $this->addFlash('success', $this->translator->trans('back.season.edit.success'));

            return $this->redirectToRoute('bo_season_list');
        }

        return $this->render('back/season/edit.html.twig', [
            'form' => $form->createView(),
            'season' => $season,
        ]);
    }

    #[Route('/saison-sportive/activer/{season}', name: 'bo_season_activate', methods: ['POST'])]
    public function activate(Request $request, ActivateSeasonHandler $activateSeasonHandler, Season $season): Response
    {
        if ($this->isCsrfTokenValid('activate-'.$season->getId(), (string) $request->request->get('_token'))) {
            $activateSeasonHandler->handle(new ActivateSeasonCommand($season));

            $this->addFlash('info', $this->translator->trans('back.season.activate.success'));
        }

        return $this->redirectToRoute('bo_season_list');
    }
}
