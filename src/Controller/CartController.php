<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/cart', name: 'app_cart_')]
final class CartController extends AbstractController
{
    #[Route('/', name: 'cart_index', methods: ['POST','GET'])]
    public function index(SessionInterface $session, ArticleRepository $articleRepository)
    {
        $cart = $session->get('cart', []);
        $data = [];
        $total = 0;
        foreach ($cart as $id=>$quantity) {
            $article = $articleRepository->find($id);
            if ($article) {
                $data[] = ['article' => $article, 'quantity' => $quantity];
                $total += $article->getPrice() * $quantity;
                $session->set('cart_total', $total);
            }
        }
        return $this->render('cart/cart.html.twig',compact('data','total','cart') + [
            'items' => $data,
            'total' => $total,
            'cart' => $cart,
        ]);
        return $this->redirectToRoute('app_accueil');
    }
    #[Route('/add/{id}', name: 'add_to_cart', methods: ['POST','GET'])]
    public function add(int $id, Request $request, ArticleRepository $articleRepository, SessionInterface $session): Response
    {
        $article = $articleRepository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }
        $quantity = max(1, (int) $request->request->get('quantity', 1));
        $cart = $session->get('cart', []);
        if (!isset($cart[$id])) {
        $cart[$id] = $quantity;
        } 
        else {
            $cart[$id] += $quantity;
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute('app_cart_cart_index');
    }
    #[Route('/decrease/{id}', name: 'decrease_from_cart', methods: ['POST','GET'])]
    public function decrease(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        if (isset($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
            $session->set('cart', $cart);
        }
        return $this->redirectToRoute('app_cart_cart_index');
    }
    #[Route('/remove/{id}', name: 'remove_from_cart', methods: ['POST','GET'])]
    public function remove(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $session->set('cart', $cart);
        }
        return $this->redirectToRoute('app_cart_cart_index');
    }
    #[Route('/purchase', name:'purchase', methods: ['POST','GET'])]
    public function purchase(SessionInterface $session, UserRepository $userRepository): Response
    {   
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $wallet = $user->getWallets()->first();
        $cartTotal = $session->get('cart_total', 0);
        if ($wallet->getBalance() < $cartTotal) {
            $this->addFlash('error', 'Solde insuffisant pour effectuer cet achat.');
            return $this->redirectToRoute('app_cart_cart_index');
        }
        $wallet->setBalance($wallet->getBalance() - $cartTotal);
        $userRepository->save($user, true);
        $session->remove('cart');
        $session->remove('cart_total');
        return $this->redirectToRoute('app_cart_purchase_success');
    }
    #[Route('/purchase/success', name:'purchase_success', methods: ['GET'])]
    public function purchaseSuccess(): Response
    {
        $user = $this->getUser();

        return $this->render('cart/purchase.html.twig', [
        'user' => $user,
    ]);
    }

}
