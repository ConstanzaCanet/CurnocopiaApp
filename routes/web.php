<?php

use App\Http\Controllers\AuthController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {return view('/auth.login');});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
        Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');});    


Route::get('/auth/redirect', [AuthController::class, 'redirect']);
Route::get('/auth/google/callback-url', [AuthController::class, 'callback']);


Route::resource('products', ProductController::class);
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::post('');



/*Route::get('/products', [ProductController::class, 'index'])->name('products.create');
Route::get('/products/crear', [ProductController::class, 'create'])->name('products.create');
Route::post('products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
*/