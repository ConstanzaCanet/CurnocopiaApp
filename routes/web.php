<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Socialite\Facades\Socialite;

use function Livewire\store;

Route::get('/', function () {return view('/auth.login');});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
        Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');});    


Route::get('/auth/redirect', [AuthController::class, 'redirect']);
Route::get('/auth/google/callback-url', [AuthController::class, 'callback']);


Route::resource('products', ProductController::class);
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');

Route::delete('images/{id}', [ImageController::class, 'destroy'])->name('images.destroy');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{rowId}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::put('/cart/{rowId}', [CartController::class, 'update'])->name('cart.update');


Route::get('/checkout', [OrderController::class, 'index'])->name('cart.checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');






//Route::delete('products/delete-image/{id}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');

/*Route::get('/products', [ProductController::class, 'index'])->name('products.create');
Route::get('/products/crear', [ProductController::class, 'create'])->name('products.create');
Route::post('products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
*/