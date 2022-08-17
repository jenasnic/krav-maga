<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\SaveAdherentCommand;
use App\Domain\Command\Back\SaveAdherentHandler;
use App\Entity\Adherent;
use App\Form\AdherentType;
use App\Repository\AdherentRepository;
use App\Repository\RegistrationRepository;
use App\Repository\SeasonRepository;
use App\Service\Grid\AdherentFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdherentController extends AbstractController
{
    public function __construct(
        protected SeasonRepository $seasonRepository,
        protected AdherentRepository $adherentRepository,
        protected RegistrationRepository $registrationRepository,
        protected TranslatorInterface $translator,
    ) {
    }

    #[Route('/adherent/liste/{filter}', name: 'bo_adherent_list', methods: ['GET'])]
    public function list(AdherentFilter $adherentFiler, ?string $filter = null): Response
    {
        $season = $this->seasonRepository->getActiveSeason();

        if (null === $season) {
            $this->addFlash('warning', $this->translator->trans('back.season.activate.missingSeason'));

            return $this->redirectToRoute('bo_season_list');
        }

        /** @var int $seasonId */
        $seasonId = $season->getId();

        $queryBuilder = $this->adherentRepository->createSearchQueryBuilder($seasonId);

        $queryBuilder = $adherentFiler->apply($queryBuilder, $filter);

        return $this->render('back/adherent/list.html.twig', [
             'registrations' => $queryBuilder->getQuery()->getResult(),
             'filters' => $adherentFiler->getFilters(),
         ]);
    }

    #[Route('/adherent/liste-complete', name: 'bo_adherent_full_list', methods: ['GET'])]
    public function listAll(AdherentFilter $adherentFiler, ?string $filter = null): Response
    {
        $queryBuilder = $this->adherentRepository->createSearchAllQueryBuilder();

        return $this->render('back/adherent/list_full.html.twig', [
             'registrations' => $queryBuilder->getQuery()->getResult(),
         ]);
    }

    #[Route('/adherent/modifier/{adherent}', name: 'bo_adherent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SaveAdherentHandler $saveAdherentHandler, Adherent $adherent): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saveAdherentHandler->handle(new SaveAdherentCommand($adherent));

            $this->addFlash('info', $this->translator->trans('back.adherent.edit.success'));

            return $this->redirectToRoute('bo_adherent_list');
        }

        /** @var int $adherentId */
        $adherentId = $adherent->getId();

        return $this->render('back/adherent/edit.html.twig', [
             'form' => $form->createView(),
             'adherent' => $adherent,
             'registration' => $this->registrationRepository->getForAdherent($adherentId),
         ]);
    }

    #[Route('/adherent/supprimer/{adherent}', name: 'bo_adherent_delete', methods: ['POST'])]
    public function delete(Request $request, Adherent $adherent): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$adherent->getId(), (string) $request->request->get('_token'))) {
            $this->adherentRepository->remove($adherent, true);

            $this->addFlash('info', $this->translator->trans('back.adherent.delete.success'));
        }

        if ($request->request->get('full-list', false)) {
            return $this->redirectToRoute('bo_adherent_full_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('bo_adherent_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/adherent/trombinoscope', name: 'bo_adherent_gallery', methods: ['GET'])]
    public function gallery(): Response
    {
        return $this->render('back/adherent/gallery.html.twig', [
            'adherents' => $this->adherentRepository->findForGallery(),
        ]);
    }
}
