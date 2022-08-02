<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SportingController extends AbstractController
{
    #[Route('/disciplines', name: 'app_sporting')]
    public function index(): Response
    {
        return $this->render('front/sporting.html.twig');
    }
}
