<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    public function searchByTerm(string $term): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :term')
            ->orWhere('a.content LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByFilters(?Category $category, ?float $maxPrice)
    {
        $qb = $this->createQueryBuilder('a');
        if ($category) {
            $qb->andWhere('a.category = :category')
            ->setParameter('category', $category);
        }
        // dd($category);
        // if ($maxPrice) {
        //     $qb->andWhere('a.price <= :maxPrice')
        //     ->setParameter('maxPrice', $maxPrice);
        // }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
