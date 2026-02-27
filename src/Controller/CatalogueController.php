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
        if ($categoryName) {
            $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        }
        else {
            $category = null;
        }
        if ($maxPrice) {
            $maxPrice = (float)$maxPrice;
        }
        $articles = $articleRepository->findByFilters($category, $maxPrice);
        return $this->render('catalogue/catalogue.html.twig', [
            'maxPrice' => $maxPrice,
            'category' => $category,
            'articles' => $articles,
        ]);
    }
}
