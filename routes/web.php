<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Models\Order;
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

Route::get('callback{order:uuid}',[OrderController::class,'callback'])->name('config');


// Rutas para facturación
Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index'); // Lista de facturas
Route::get('invoices/generate/{order}', [InvoiceController::class, 'generate'])->name('invoices.generate');
Route::get('invoices/{order}', [InvoiceController::class, 'show'])->name('invoices.show'); // Mostrar factura

Route::get('/invoice/{order}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');




Route::middleware(['auth'])->group(function () {
        // Ruta para los productos del usuario
        Route::get('/my-products', [ProductController::class, 'myProducts'])->name('products.my');
        
        // Rutas para editar y eliminar productos
        Route::get('/my-products/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/my-products/update/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/my-products/delete/{product}', [ProductController::class, 'destroy'])->name('products.delete');
    });
//Route::delete('products/delete-image/{id}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');

/*Route::get('/products', [ProductController::class, 'index'])->name('products.create');
Route::get('/products/crear', [ProductController::class, 'create'])->name('products.create');
Route::post('products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
*/