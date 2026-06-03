<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderItemController extends AbstractController
{
    #[Route('/order', name: 'app_orders_index')]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findBy(
            ['user' => $user],
            ['id' => 'DESC']
        );

        return $this->render('order_item/index.html.twig', [
            'orders' => $orders,
        ]);
    }
}
