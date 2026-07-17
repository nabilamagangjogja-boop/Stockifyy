<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use App\Models\ProductAttribute;

interface ProductAttributeRepositoryInterface
{
    public function create(Product $product, array $data): ProductAttribute;

    public function delete(ProductAttribute $attribute): void;
}
