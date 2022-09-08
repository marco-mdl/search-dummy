<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\IndexingForm;
use App\Form\SearchForm;
use App\Service\IndexingService;
use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('search');
    }

    #[Route('/search', name: 'search')]
    public function search(Request $request, SearchService $searchService): Response
    {
        $search = new Search();

        $form = $this->createForm(SearchForm::class, $search);

        $form->handleRequest($request);
        $searchResponse = null;
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $search->setFacets($request->get('search_form')['facets'] ?? []);
                $searchResponse = $searchService->search($search);


            } catch (Throwable $exception) {
                $this
                    ->addFlash(
                        'danger',
                        $exception->getMessage()
                    );
            }
        }
        return $this->render(
            'index/index.html.twig',
            [
                'form' => $form->createView(),
                'searchResponse' => $searchResponse,
                'search' => $search,
            ]
        );
    }

    #[Route('/indexing', name: 'indexing')]
    public function indexing(
        Request $request,
        IndexingService $indexingService
    ): Response
    {
        $form = $this->createForm(IndexingForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $indexingResult = $indexingService->indexingFiles($form->getData()['files']);
            dump($indexingResult);
            } catch (Throwable $exception) {
                $this
                    ->addFlash(
                        'danger',
                        $exception->getMessage()
                    );
            }
        }
        return $this->render(
            'indexing/indexing.html.twig',
            [
                'form' => $form->createView(),
                'indexingResult' => $indexingResult ?? null,
            ]
        );
    }
}
