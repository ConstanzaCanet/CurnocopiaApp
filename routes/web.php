<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Models\User;
use Illuminate\Support\Facades\Auth as FacadesAuth;

use function Livewire\store;

Route::get('/', function () {return view('/auth.login');});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
        Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');});    


Route::get('/auth/redirect', [AuthController::class, 'redirect']);
Route::get('/auth/google/callback-url', [AuthController::class, 'callback']);


//Rutas que atañen al usuario
Route::middleware(['auth'])->group(function () {
        // Rutas productos
        Route::resource('products', ProductController::class);
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('images/{id}', [ImageController::class, 'destroy'])->name('images.destroy');
        Route::get('/categoria/{id}', [ProductController::class, 'byCategory'])->name('products.byCategory');
        Route::get('/search', [ProductController::class, 'search'])->name('products.search');
        
        // Ruta para los productos del usuario
        Route::get('/my-products', [ProductController::class, 'myProducts'])->name('products.my');
        Route::get('/my-products/edit/{product}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/my-products/update/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/my-products/delete/{product}', [ProductController::class, 'destroy'])->name('products.delete');
        
        //Rutas carrito
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
        Route::patch('/cart/update/{rowId}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{rowId}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::put('/cart/{rowId}', [CartController::class, 'update'])->name('cart.update');

        // Rutas para pago
        Route::get('/checkout', [OrderController::class, 'index'])->name('cart.checkout');
        Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
        Route::get('callback{order:uuid}',[OrderController::class,'callback'])->name('config');

        // Rutas para facturación
        Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/generate/{order}', [InvoiceController::class, 'generate'])->name('invoices.generate');
        Route::get('invoices/{order}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoice/{order}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
        
        // Rutas comentarios
        Route::post('/comments/{product}', [CommentController::class, 'store'])->name('comments.store');

        //wishlist
        Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggleWishlist'])->name('wishlist.toggle');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});


//Rutas que atañen al administrador
Route::group(['middleware' => \App\Http\Middleware\AdminMiddleware::class], function () {
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/admin/users/{user}', [AdminController::class, 'show'])->name('admin.users.show');
        Route::delete('/admin/user/{user}',[UserController::class,'destroy'])->name('admin.users.destroy');
        Route::post('/admin/users/{user}/send-message',[AdminController::class,'sendMessage'])->name('admin.users.sendMessage');


        Route::get('admin/products/show/{product}', [AdminController::class, 'show'])->name('admin.products.show');
        Route::delete('/admin/products/{product}', [AdminController::class, 'destroy'])->name('admin.products.destroy');
        
        Route::get('admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::post('admin/orders/{order}/send-invoice', [OrderController::class, 'sendInvoice'])->name('admin.orders.sendInvoice');
        
        Route::delete('/admin/categories/{category}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    });

