<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart', name: 'app_cart')]
final class CartController extends AbstractController
{
    #[Route('/', name: 'cart_index', methods: ['GET'])]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }
    #[Route('/add/{id}', name: 'add_to_cart', methods: ['POST'])]
    public function add(Article $article, SessionInterface $session): void
    {
        $id = $article->getId();
        $cart = $session->get('cart', []);
        if (!in_array($id, $cart, true)) {
            $cart[] = $id;
            $session->set('cart', $cart);
        }
    }
}
