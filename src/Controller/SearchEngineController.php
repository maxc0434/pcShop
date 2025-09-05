<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchEngineController extends AbstractController
{
    #[Route('/search/engine', name: 'app_search_engine')]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        if ($request->isMethod('GET')) {
            $data = $request->query->all();
            $word = $data['word'];
            $results = $articleRepository->searchEngine($word);
        }
        if ($results == []) {
            $this->addFlash('warning', 'pas de produit a ce nom');
            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('search_engine/index.html.twig', [
            'articles' => $results,
            'word' => $word
        ]);
    }

    // Pour que la search de la nav fonctionne il faut créer une fonction dans le ArticleRepository 
}
