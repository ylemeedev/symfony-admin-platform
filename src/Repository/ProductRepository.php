<?php

namespace App\Repository;

use App\Dto\ProductStockDto;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllWithTotalStock()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.stocks', 's')
            ->select('p AS product')
            ->addSelect('COALESCE(SUM(s.quantity), 0) AS totalStock')
            ->groupBy('p.id');
/*             ->getQuery()
            ->getResult();

        return array_map(
            fn (array $row) => new ProductStockDto(
                $row['product'],
                (int) $row['totalStock']
            ),
            $rows
        ); */
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
