<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\ArticleRepository;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        $articles = $articleRepository->findAll();
        return $this->render('accueil/acceuil.html.twig', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }
}
