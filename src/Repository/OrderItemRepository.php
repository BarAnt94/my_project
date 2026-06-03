<?php

namespace App\Repository;

use App\Entity\OrderItem;
use App\Entity\Order;
use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderItem>
 */
class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }
    public function createOrderItems(array $cart, User $user): void
    {
        $em = $this->getEntityManager();
        foreach ($cart as $articleId => $quantity) {
            $orderItem = new OrderItem();
            $article = $em->getRepository(Article::class)->find($articleId);
            $order = $em->getRepository(Order::class)->findOneBy(['user' => $user], ['date' => 'DESC']);
            $orderItem->setArticle($article);
            $orderItem->setOrder($order);
            $orderItem->setQuantity($quantity);
            $orderItem->setPrice($article->getPrice());
            $orderItem->setUser($user);
            $em->persist($orderItem);
        }
        $em->flush();
    }
//    /**
//     * @return OrderItem[] Returns an array of OrderItem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OrderItem
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
