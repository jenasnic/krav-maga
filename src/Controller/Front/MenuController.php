<?php

namespace App\Controller\Front;

use App\Repository\Content\NewsRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    public function menu(SeasonRepository $seasonRepository, NewsRepository $newsRepository): Response
    {
        $activeSeason = $seasonRepository->getActiveSeason();
        $now = (new \DateTime())->setTime(0, 0, 0);
        $canRegister = !(
            null === $activeSeason
            || $now < $activeSeason->getStartDate()
            || $now > $activeSeason->getEndDate()
        );

        $hasNews = !empty($newsRepository->findOrdered());

        return $this->render('front/components/_menu.html.twig', [
            'canRegister' => $canRegister,
            'hasNews' => $hasNews,
        ]);
    }
}
