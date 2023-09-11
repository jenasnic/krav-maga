<?php

namespace App\Controller\Front;

use App\Entity\Content\News;
use App\Repository\Content\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/actualites', name: 'app_news')]
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('front/news.html.twig', [
            'newsList' => $newsRepository->findOrdered(),
        ]);
    }

    #[Route('/image-actualite/{news}', name: 'app_news_picture')]
    public function picture(News $news): Response
    {
        if (null === $news->getPictureUrl()) {
            throw $this->createNotFoundException();
        }

        return $this->file($news->getPictureUrl());
    }
}
