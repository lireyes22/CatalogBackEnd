<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');
Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy'); */


/* Route::apiResource('clients', ClientController::class)
->names('api.clients')
->middleware('hasToken'); */

/* Route::apiResource('users', UserController::class)
->names('api.users')
->middleware('hasToken'); */

/* //Authentication with google for clients
Route::post('oauth/client', [OAuthController::class , 'client'])->name('oauth.client')->middleware('hasToken');
//Authentication with google for suppliers
Route::post('oauth/supplier', [OAuthController::class , 'supplier'])->name('oauth.supplier')->middleware('hasToken'); */

/* Route::apiResource('products', ProductController::class)
    ->names('api.products')
    ->middleware('hasToken'); */

//Route::get('products/slug/{slug}', [ProductController::class, 'slug'])->name('products.slug')->middleware('hasToken');

Route::prefix('oauth')->group(function () {
    Route::post('client', [OAuthController::class, 'client'])->name('oauth.client')->middleware('hasToken');
    Route::post('supplier', [OAuthController::class, 'supplier'])->name('oauth.supplier')->middleware('hasToken');
});

//Router for products with slug
Route::get('products/slug/{slug}', [ProductController::class, 'slug']);
//Router for products with index and show
Route::resource('products', ProductController::class)
    ->only(['index', 'show'])
    ->names('api.products');

//Router for products with store, update and destroy - access only for suppliers
Route::resource('products', ProductController::class)
    ->only(['store', 'update', 'destroy'])
    ->names('api.products')
    ->middleware(['hasToken', 'isUser:supplier']);

Route::apiResource('orders', OrderController::class)
    ->names('api.orders');