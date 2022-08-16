<?php

namespace App\Controller\Front;

use App\Repository\Payment\PriceOptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PriceOptionRepository $priceOptionRepository): Response
    {
        return $this->render('front/home.html.twig', [
            'priceOptions' => $priceOptionRepository->findAllOrdered(),
        ]);
    }
}
