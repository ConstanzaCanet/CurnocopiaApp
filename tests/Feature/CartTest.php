<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
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

    public function test_it_can_add_products_to_cart()
    {
        $this->withoutExceptionHandling();
        $this->withSession([]);
        $user = User::factory()->create();
        $this->actingAs($user);
    
        $product = Product::factory()->create([
            'stock_quantity' => 10,
        ]);
        $this->post(route('cart.add', $product->id));
        $cartCount = Cart::count();
        $this->assertEquals(1, $cartCount);

        $cart = Cart::content();
        $cartItem = $cart->firstWhere('id', $product->id);
        $this->assertEquals(1, $cartItem->qty);
        $this->assertEquals($product->price, $cartItem->price);
    }

}
