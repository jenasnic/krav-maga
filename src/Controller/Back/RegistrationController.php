<?php

namespace App\Controller\Back;

use App\Domain\Command\Front\RegistrationCommand;
use App\Entity\Adherent;
use App\Form\AdherentType;
use App\Repository\AdherentRepository;
use App\Repository\RegistrationInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        protected AdherentRepository $adherentRepository,
    ) {
    }

    #[Route('/inscription/liste', name: 'bo_registration_list')]
    public function list(RegistrationInfoRepository $registrationInfoRepository): Response
    {
        return $this->render('back/registration/list.html.twig', [
             'registrations' => $registrationInfoRepository->search(),
         ]);
    }

    #[Route('/inscription/ajouter', name: 'bo_registration_add')]
    public function add(Request $request): Response
    {
        return $this->edit($request, new Adherent());
    }

    #[Route('/inscription/consulter/{adherent}', name: 'bo_registration_edit')]
    public function edit(Request $request, Adherent $adherent): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->adherentRepository->add($adherent, true);

            // @todo : add flash message to inform user email has been sent (for validation...)

            return $this->redirectToRoute('app_home');
        }

         return $this->render('back/registration/edit.html.twig', [
             'form' => $form->createView(),
             'adherent' => $adherent,
         ]);
    }

    #[Route('/inscription/supprimer/{adherent}', name: 'bo_registration_delete', methods: ['DELETE'])]
    public function delete(Request $request, Adherent $adherent): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$adherent->getId(), (string) $request->request->get('_token'))) {
            $this->adherentRepository->remove($adherent, true);
        }

        return $this->redirectToRoute('bo_registration_list', [], Response::HTTP_SEE_OTHER);
    }
}
