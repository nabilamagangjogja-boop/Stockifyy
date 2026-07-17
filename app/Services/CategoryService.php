<?php

namespace App\Services;

use App\Exceptions\HasRelatedDataException;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categories,
        protected ActivityLogService $activityLog
    ) {}

    public function all(): Collection
    {
        return $this->categories->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->categories->paginate($perPage);
    }

    public function create(array $data): Category
    {
        $validated = Validator::make($data, [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
        ], [
            'name.max' => 'Nama kategori maksimal 50 karakter.',
        ])->validate();

        $category = $this->categories->create($validated);
        $this->activityLog->log('create', 'Kategori', "Menambahkan kategori \"{$category->name}\".");
        return $category;
    }

    public function update(Category $category, array $data): Category
    {
        $validated = Validator::make($data, [
            'name' => 'required|string|max:50',
            'description' => 'nullable|string',
        ], [
            'name.max' => 'Nama kategori maksimal 50 karakter.',
        ])->validate();

        $category = $this->categories->update($category, $validated);
        $this->activityLog->log('update', 'Kategori', "Memperbarui kategori \"{$category->name}\".");
        return $category;
    }

    public function delete(Category $category): void
    {
        // Kategori masih dipakai produk aktif? Tolak dengan pesan yang jelas,
        // daripada mengandalkan error DB (FK restrict) yang membingungkan user.
        // ->products() otomatis hanya menghitung produk yang belum di-soft-delete.
        if ($category->products()->exists()) {
            throw new HasRelatedDataException(
                'Kategori "' . $category->name . '" masih dipakai oleh satu atau lebih produk aktif. '
                    . 'Pindahkan produknya ke kategori lain atau hapus dulu produknya sebelum menghapus kategori ini.'
            );
        }

        $this->categories->delete($category);
        $this->activityLog->log('delete', 'Kategori', "Menghapus kategori \"{$category->name}\".");
    }
}
