<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AdressRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandController extends AbstractController
{
    #[Route('/command', name: 'command')]
    public function index(Request $request, ArticleRepository $articleRepository, AdressRepository $adressRepository, SessionInterface $session): Response
    {
        $user = $this->getUser();
        $adresses = $adressRepository->findBy(['user' => $user]);
        $articles = $articleRepository->findAll();
        return $this->render('command/index.html.twig', [
            'adresses' => $adresses,
            'articles' => $articles,
        ]);
    }
}
