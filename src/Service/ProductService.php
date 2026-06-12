<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function create(Product $product): Product
    {
        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function remove(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}
