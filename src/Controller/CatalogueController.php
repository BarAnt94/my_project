<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogueController extends AbstractController
{
    #[Route('/catalogues', name: 'app_catalogues')]
    public function index(Request $request, ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $categoryName = $request->query->get('category');
        $maxPrice = $request->query->get('maxPrice');
        $categories = $categoryRepository->findAll();
        $articles = $articleRepository->findByFilters($categoryName ? $categoryRepository->findOneBy(['name' => $categoryName]) : null, $maxPrice ? (float)$maxPrice : null);
        return $this->render('catalogue/catalogue.html.twig', [
            'categories' => $categories,    
            'category' => $categoryName,
            'maxPrice' => $maxPrice,
            'articles' => $articles
        ]);
    }
}
