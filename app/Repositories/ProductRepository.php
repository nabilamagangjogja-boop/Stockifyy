<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function allWithRelations(): Collection
    {
        return Product::with(['category', 'supplier', 'attributes'])->get();
    }

    public function paginateWithRelations(int $perPage = 10): LengthAwarePaginator
    {
        return Product::with(['category', 'supplier', 'attributes'])->latest()->paginate($perPage);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
