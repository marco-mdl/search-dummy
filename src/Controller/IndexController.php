<?php

namespace App\Controller;

use App\Entity\Indexing;
use App\Entity\Search;
use App\Form\IndexingForm;
use App\Form\SearchForm;
use App\Service\IndexingService;
use App\Service\SearchService;
use App\Service\SuggestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
                $search->setManufacturer($request->get('search_form')['manufacturer'] ?? []);
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

    #[Route('/suggest', name: 'suggest')]
    public function suggest(Request $request, SuggestService $suggestService): Response
    {
        try {
            $response = $suggestService->suggest($request->get('q'));

        } catch (Throwable $exception) {
            $response =
                [
                    'error' => $exception->getMessage()
                ];
        } finally {
            return $this->json($response);
        }
    }

    #[Route('/indexing', name: 'indexing')]
    public function indexing(
        Request         $request,
        IndexingService $indexingService
    ): Response
    {
        $indexing = new Indexing();
        $form = $this->createForm(IndexingForm::class, $indexing);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $indexingResult = $indexingService->indexingFiles($indexing->getFiles(), $indexing->getIndex());
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
