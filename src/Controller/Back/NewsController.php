<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\Content\SaveNewsCommand;
use App\Domain\Command\Back\Content\SaveNewsHandler;
use App\Entity\Content\News;
use App\Form\Content\NewsRankType;
use App\Form\Content\NewsType;
use App\Repository\Content\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsController extends AbstractController
{
    public function __construct(
        protected NewsRepository $newsRepository,
        protected TranslatorInterface $translator,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/actualites/liste', name: 'bo_news_list', methods: ['GET', 'POST'])]
    public function list(Request $request): Response
    {
        $newsList = $this->newsRepository->findAllOrdered();

        $form = $this->createForm(CollectionType::class, $newsList, [
            'label' => false,
            'entry_type' => NewsRankType::class,
            'entry_options' => [
                'label' => false,
            ],
            'allow_add' => false,
            'allow_delete' => false,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->entityManager->flush();

            $this->addFlash('info', $this->translator->trans('back.news.list.order.success'));

            return $this->redirectToRoute('bo_news_list');
        }

        return $this->render('back/news/list.html.twig', [
            'form' => $form->createView(),
            'newsCount' => count($newsList),
         ]);
    }

    #[Route('/actualites/nouvelle-actualite', name: 'bo_news_new', methods: ['GET', 'POST'])]
    public function add(Request $request, SaveNewsHandler $saveNewsHandler): Response
    {
        $news = new News();

        $news->setRank($this->newsRepository->getFirstRank() - 1);

        return $this->edit($request, $saveNewsHandler, $news);
    }

    #[Route('/actualites/modifier/{news}', name: 'bo_news_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SaveNewsHandler $saveNewsHandler, News $news): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saveNewsHandler->handle(new SaveNewsCommand($news));

            $this->addFlash('info', $this->translator->trans('back.news.edit.success'));

            return $this->redirectToRoute('bo_news_list');
        }

        return $this->render('back/news/edit.html.twig', [
             'form' => $form->createView(),
             'news' => $news,
         ]);
    }

    #[Route('/actualites/supprimer/{news}', name: 'bo_news_delete', methods: ['POST'])]
    public function delete(Request $request, News $news): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$news->getId(), (string) $request->request->get('_token'))) {
            $this->newsRepository->remove($news, true);

            $this->addFlash('info', $this->translator->trans('back.news.delete.success'));
        }

        return $this->redirectToRoute('bo_news_list', [], Response::HTTP_SEE_OTHER);
    }
}
