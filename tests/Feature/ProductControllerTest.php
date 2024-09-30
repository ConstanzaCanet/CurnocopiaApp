<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
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
    // Crear un usuario y autenticarlo
    $user = User::factory()->create();
    $this->actingAs($user);

    // Datos para el producto a crear
    $productData = [
        'name' => 'Test Product',
        'description' => 'This is a test product',
        'price' => 100,
        'user_id' => $user->id,
        // Si tienes más campos requeridos, agrégalos aquí
    ];

    // Hacer la solicitud para crear el producto
    $response = $this->post(route('products.store'), $productData);

    // Verificar que la respuesta es una redirección
    $response->assertStatus(302);

    // Verificar que el producto fue creado en la base de datos
    $this->assertDatabaseHas('products', [
        'name' => 'Test Product',
        'description' => 'This is a test product',
        'price' => 100,
        'user_id' => $user->id,
    ]);
}



    public function test_it_can_update_a_product()
    {
        // Crear un usuario y autenticarlo
        $user = User::factory()->create();
        $this->actingAs($user);
    
        // Crear un producto asociado a este usuario
        $product = Product::factory()->create([
            'name' => 'Old Product Name',
            'price' => 50,
            'user_id' => $user->id,
        ]);
    
        // Datos actualizados para el producto
        $updatedData = [
            'name' => 'New Product Name',
            'price' => 75,
            'description' => $product->description, // si es necesario
            'stock_quantity' => $product->stock_quantity,
            'category_id' => $product->category_id,
        ];
    
        // Hacer la solicitud para actualizar el producto
        $response = $this->put(route('products.update', $product->id), $updatedData);
    
        // Verificar que la respuesta es exitosa
        $response->assertStatus(302);
    
        // Verificar que los datos del producto han sido actualizados en la base de datos
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Product Name',
            'price' => 75,
        ]);
    }
    
    public function test_it_can_delete_a_product()
{
    // Crear un usuario y autenticarlo
    $user = User::factory()->create();
    $this->actingAs($user);

    // Crear un producto
    $product = Product::factory()->create([
        'name' => 'Product to Delete',
        'user_id' => $user->id,
    ]);

    // Hacer la solicitud para eliminar el producto
    $response = $this->delete(route('products.destroy', $product->id));

    // Verificar que la respuesta es exitosa
    $response->assertStatus(302);

    // Verificar que el producto fue eliminado de la base de datos
    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
        'name' => 'Product to Delete',
    ]);
}

public function test_it_can_list_products()
{
    // Crear un usuario y autenticarlo
    $user = User::factory()->create();
    $this->actingAs($user);

    // Crear productos
    $products = Product::factory(3)->create();

    // Hacer una solicitud para obtener la lista de productos
    $response = $this->get(route('products.index'));

    // Verificar que la respuesta es exitosa
    $response->assertStatus(200);

    // Verificar que los productos están en la vista
    $response->assertViewHas('products', Product::all());
}

}
