<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Repositories\Contracts\ProductAttributeRepositoryInterface;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    public function create(Product $product, array $data): ProductAttribute
    {
        return $product->attributes()->create($data);
    }

    public function delete(ProductAttribute $attribute): void
    {
        $attribute->delete();
    }
}
