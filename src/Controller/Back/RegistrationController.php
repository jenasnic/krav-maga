<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\RemoveAdherentCommand;
use App\Domain\Command\Back\RemoveAdherentHandler;
use App\Domain\Command\Back\SaveAdherentCommand;
use App\Domain\Command\Back\SaveAdherentHandler;
use App\Entity\Adherent;
use App\Form\AdherentType;
use App\Repository\RegistrationInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription/liste/{filter}', name: 'bo_registration_list')]
    public function list(RegistrationInfoRepository $registrationInfoRepository, ?string $filter = null): Response
    {
        return $this->render('back/registration/list.html.twig', [
             'registrations' => $registrationInfoRepository->search($filter),
         ]);
    }

    #[Route('/inscription/ajouter', name: 'bo_registration_add')]
    public function add(Request $request, SaveAdherentHandler $saveAdherentHandler): Response
    {
        return $this->edit($request, $saveAdherentHandler, new Adherent());
    }

    #[Route('/inscription/consulter/{adherent}', name: 'bo_registration_edit')]
    public function edit(Request $request, SaveAdherentHandler $saveAdherentHandler, Adherent $adherent): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saveAdherentHandler->handle(new SaveAdherentCommand($adherent));

            // @todo : add flash message to inform user email has been sent (for validation...)

            return $this->redirectToRoute('bo_registration_list');
        }

        return $this->render('back/registration/edit.html.twig', [
             'form' => $form->createView(),
             'adherent' => $adherent,
         ]);
    }

    #[Route('/inscription/supprimer/{adherent}', name: 'bo_registration_delete', methods: ['POST'])]
    public function delete(Request $request, RemoveAdherentHandler $removeAdherentHandler, Adherent $adherent): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$adherent->getId(), (string) $request->request->get('_token'))) {
            $removeAdherentHandler->handle(new RemoveAdherentCommand($adherent));
        }

        return $this->redirectToRoute('bo_registration_list', [], Response::HTTP_SEE_OTHER);
    }
}
