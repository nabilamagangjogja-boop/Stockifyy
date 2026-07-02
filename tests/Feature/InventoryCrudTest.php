<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class InventoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_product_image_upload_flow(): void
    {
        $user = User::factory()->create(['role' => 'Admin']);
        $category = Category::create(['name' => 'Elektronik']);
        $supplier = Supplier::create(['name' => 'Supplier A']);

        $this->actingAs($user);

        $response = $this->post('/products', [
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'name' => 'Laptop',
            'sku' => 'LT001',
            'purchase_price' => 5000000,
            'selling_price' => 6500000,
            'minimum_stock' => 3,
            'image' => UploadedFile::fake()->create('laptop.png', 100, 'image/png'),
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['sku' => 'LT001']);
    }

    public function test_category_crud_flow(): void
    {
        $user = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($user);

        $response = $this->get('/categories');
        $response->assertStatus(200);

        $response = $this->post('/categories', [
            'name' => 'Elektronik',
            'description' => 'Peralatan elektronik',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Elektronik']);

        $category = Category::latest()->first();

        $response = $this->get('/categories/' . $category->id . '/edit');
        $response->assertStatus(200);

        $response = $this->put('/categories/' . $category->id, [
            'name' => 'Elektronik Premium',
            'description' => 'Peralatan elektronik premium',
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Elektronik Premium']);

        $response = $this->delete('/categories/' . $category->id);
        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
