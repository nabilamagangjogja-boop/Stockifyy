<?php

namespace App\Services;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class SupplierService
{
    public function __construct(
        protected SupplierRepositoryInterface $suppliers,
        protected ActivityLogService $activityLog
    ) {}

    public function all(): Collection
    {
        return $this->suppliers->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->suppliers->paginate($perPage);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'address' => 'nullable|string',
            'phone' => ['nullable', 'max:15', 'regex:/^[0-9+\-\s()]*$/'],
            'email' => 'nullable|email|max:50',
        ];
    }

    protected function messages(): array
    {
        return [
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka (boleh pakai +, -, spasi, atau tanda kurung).',
            'name.max' => 'Nama supplier maksimal 100 karakter.',
            'phone.max' => 'Nomor telepon maksimal 15 karakter.',
            'email.max' => 'Email maksimal 50 karakter.',
        ];
    }

    public function create(array $data): Supplier
    {
        $validated = Validator::make($data, $this->rules(), $this->messages())->validate();
        $supplier = $this->suppliers->create($validated);
        $this->activityLog->log('create', 'Supplier', "Menambahkan supplier \"{$supplier->name}\".");
        return $supplier;
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $validated = Validator::make($data, $this->rules(), $this->messages())->validate();
        $supplier = $this->suppliers->update($supplier, $validated);
        $this->activityLog->log('update', 'Supplier', "Memperbarui supplier \"{$supplier->name}\".");
        return $supplier;
    }

    public function delete(Supplier $supplier): void
    {
        $this->suppliers->delete($supplier);
        $this->activityLog->log('delete', 'Supplier', "Menghapus supplier \"{$supplier->name}\".");
    }
}
