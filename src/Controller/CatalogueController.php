<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogueController extends AbstractController
{
    #[Route('/catalogues', name: 'app_catalogues')]
    public function index(Request $request, ArticleRepository $catalogueRepository): Response
    {
        $category = $request->query->get('category');
        $maxPrice = $request->query->get('maxPrice');
        $articles = $catalogueRepository->findByFilters($category, $maxPrice);
        return $this->render('catalogue/catalogue.html.twig', [
            'maxPrice' => $maxPrice,
            'catalogues' => $catalogueRepository->findAll(),
            'articles' => $articles,
        ]);
    }
}
