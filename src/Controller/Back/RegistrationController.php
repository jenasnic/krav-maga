<?php

namespace App\Controller\Back;

use App\Repository\RegistrationInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription/liste', name: 'bo_registration_list')]
    public function list(RegistrationInfoRepository $registrationInfoRepository): Response
    {
        return $this->render('back/registration/list.html.twig', [
             'registrations' => $registrationInfoRepository->search(),
         ]);
    }

    #[Route('/inscription/consulter/{registration}', name: 'bo_registration_view')]
    public function view(): Response
    {
        throw $this->createNotFoundException();
        // return $this->render('back/registration/view.html.twig');
    }
}
