<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Repository\AdressRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommandController extends AbstractController
{
    #[Route('/command', name: 'get_command',methods: ['POST','GET'])]
    public function getCommand(ArticleRepository $articleRepository, Request $request, SessionInterface $session, AdressRepository $adressRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $adresses = $adressRepository->findBy(['user' => $user]);
        $data = [];
        $total = 0;
        $cart = $session->get('cart', []);
        foreach ($cart as $id => $qty) {
            $article = $articleRepository->find($id);
                if ($article) {
                $data[] = ['article' => $article, 'quantity' => $qty];
                $total += $article->getPrice() * $qty;
            }
        }
        $renderData = [
            'adresses' => $adresses,
            'items' => $data,
            'total' => $total,
            'cart' => $cart,
        ];
        return $this->render('command/index.html.twig', $renderData);   
    }
    #[Route('/command/purchase', name:'command_purchase', methods: ['POST','GET'])]
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
            return $this->redirectToRoute('command');
        }
        if ($cartTotal <= 0) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('get_command');
        }
        $wallet->setBalance($wallet->getBalance() - $cartTotal);
        $userRepository->save($user, true);
        $session->remove('cart');
        $session->remove('cart_total');
        return $this->redirectToRoute('command_purchase_success');
    }
    #[Route('/command/purchase/cancel', name:'command_cancel', methods: ['POST','GET'])]
    public function cancelPurchase(SessionInterface $session): Response
    {
        $session->remove('cart_total');
        $session->remove('cart_items');
        return $this->redirectToRoute('app_accueil');
    }
    #[Route('/command/purchase/success', name:'command_purchase_success', methods: ['POST','GET'])]
    public function purchaseSuccess(): Response
    {
        $user = $this->getUser();

        return $this->render('command/purchase.html.twig', [
        'user' => $user,
    ]);
    }

}
