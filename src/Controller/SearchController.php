<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, ArticleRepository $repo): Response
    {
        $query = $request->query->get('q', '');

        $results = [];
        if ($query) {
            $results = $repo->searchByTerm($query);
        }

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'results' => $results,
            'query' => $query,
        ]);
    }
}
