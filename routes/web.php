<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\MarkerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;

Route::get('/markers', [MarkerController::class, 'index']);
Route::post('/markers', [MarkerController::class, 'store']);
Route::get('/markers/{id}', [MarkerController::class, 'show']);
Route::put('/markers/{id}', [MarkerController::class, 'update']);
Route::delete('/markers/{id}', [MarkerController::class, 'destroy']);

/* Route::resource('/posts', PostController::class); */

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

/* Route::get('/posts', function () {
    return Inertia::render('PostsPage');

}); */

Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('products.index');

Route::controller(CartController::class)
->middleware('auth')
->prefix('/cart')
->name('cart.')
->group(function () {
    Route::post('/add/{product}', 'add')->name('add');
    route::get('/', 'view')->name('checkout');
    Route::post('/clear', 'clear')->name('clear');
    Route::post('/update', 'update')->name('update');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
