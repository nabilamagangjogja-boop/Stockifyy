<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Repositories\Contracts\ProductAttributeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductAttributeService
{
    public function __construct(
        protected ProductAttributeRepositoryInterface $attributes
    ) {}

    public function create(Product $product, Request $request): ProductAttribute
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'value' => 'required|string|max:100',
        ], [
            'name.required' => 'Nama atribut wajib diisi (misalnya: Ukuran, Warna, Berat).',
            'value.required' => 'Nilai atribut wajib diisi.',
            'name.max' => 'Nama atribut maksimal 50 karakter.',
            'value.max' => 'Nilai atribut maksimal 100 karakter.',
        ])->validate();

        return $this->attributes->create($product, $validated);
    }

    public function delete(ProductAttribute $attribute): void
    {
        $this->attributes->delete($attribute);
    }
}
