<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout_index')]
    public function createOrder(EntityManagerInterface $em, ArticleRepository $articleRepository): Response
    {
        $order = new Order();
        $order->setOrderNumber(uniqid('ORD-'));
        $order->setDate(new \DateTime());
        $order->setReference('REF-' . uniqid());
        $em->persist($order);
        $em->flush();

        return new Response('Order created with ID: ' . $order->getId());
    }
}