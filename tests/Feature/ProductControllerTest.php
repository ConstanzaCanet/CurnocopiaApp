<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    
    
    public function test_it_can_create_a_product()
    {
        Category::factory()->create(['id' => 2]);
        $user = User::factory()->create();
        $this->actingAs($user);
    
        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 100,
            'stock_quantity' => 1,
            'category_id' => 2,  
            'user_id' => $user->id,
        ];
    
        $response = $this->post(route('products.store'), $productData);
    
        $response->assertStatus(302);
   
        $this->assertDatabaseHas('products', $productData);
    }



    public function test_it_can_update_a_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    
        $product = Product::factory()->create([
            'name' => 'Old Product Name',
            'price' => 50,
            'user_id' => $user->id,
        ]);
    

        $updatedData = [
            'name' => 'New Product Name',
            'price' => 75,
            'description' => $product->description, 
            'stock_quantity' => $product->stock_quantity,
            'category_id' => $product->category_id,
        ];
    
        $response = $this->put(route('products.update', $product->id), $updatedData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Product Name',
            'price' => 75,
        ]);
    }
    
    public function test_it_can_delete_a_product()
{

    $user = User::factory()->create();
    $this->actingAs($user);

    $product = Product::factory()->create([
        'name' => 'Product to Delete',
        'user_id' => $user->id,
    ]);

    $response = $this->delete(route('products.destroy', $product->id));

    $response->assertStatus(302);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
        'name' => 'Product to Delete',
    ]);
}

public function test_it_can_list_products()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $products = Product::factory(3)->create();

    $response = $this->get(route('products.index'));

    $response->assertStatus(200);
    $response->assertViewHas('products', Product::all());
}

}
