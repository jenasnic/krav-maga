<?php

namespace App\Controller\Back;

use App\Repository\AdherentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdherentGalleryController extends AbstractController
{
    #[Route('/trombinoscope', name: 'bo_adherent_gallery')]
    public function gallery(AdherentRepository $adherentRepository): Response
    {
        return $this->render('back/adherent/gallery.html.twig', [
             'adherents' => $adherentRepository->findForGallery(),
         ]);
    }
}
