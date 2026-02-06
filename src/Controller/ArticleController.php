<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article->setTitle('Bouteille de vin');
        $article->setContent('Une bouteille de vin rouge de qualité.');
        $article->setCreatedAt(new \DateTime());
        $article->setPrice('19.99');
        $article->setPhoto('./img_articles/bouteille-vin-isolee-blanc_167946-4.avif'); // Placeholder for photo data
        $entityManager->persist($article);
        $entityManager->flush();
        return new Response('Article créé avec l\'ID : ' . $article->getId());
    }
    #[Route(name: 'app_article_show')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('article/article.html.twig', [
            'articles' => $article,
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route(name: 'app_article_delete')]
    public function delete(Article $article, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($article);
        $entityManager->flush();
        return new Response('Article supprimé avec succès.');
    }

}
