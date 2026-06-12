<?php

namespace App\Dto;

use App\Entity\Product;

class ProductStockDto
{
    public function __construct(
        public Product $product,
        public int $totalStock,
    ) {
    }
}
