<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [ApiController::class, 'register'])->name('auth.register');
Route::post('login', [ApiController::class, 'login'])->name('auth.login');

Route::get('dashboard', [ApiController::class, 'dashboard'])->name('dashboard.get');

Route::get('categories', [ApiController::class, 'categories'])->name('categories.get');
Route::post('save_categories', [ApiController::class, 'save_categories'])->name('save_categories.post');
Route::get('delete_categories', [ApiController::class, 'delete_categories'])->name('delete_categories.get');

Route::get('customers', [ApiController::class, 'customers'])->name('customers.get');
Route::post('save_customers', [ApiController::class, 'save_customers'])->name('save_customers.post');
Route::get('delete_customers', [ApiController::class, 'delete_customers'])->name('delete_customers.get');

Route::get('products', [ApiController::class, 'products'])->name('products.get');
Route::post('save_products', [ApiController::class, 'save_products'])->name('save_products.post');
Route::get('delete_products', [ApiController::class, 'delete_products'])->name('delete_products.get');

Route::get('orders', [ApiController::class, 'orders'])->name('orders.get');
Route::post('save_orders', [ApiController::class, 'save_orders'])->name('save_orders.post');
Route::get('delete_orders', [ApiController::class, 'delete_orders'])->name('delete_orders.get');

Route::get('admin_list', [ApiController::class, 'admin_list'])->name('admin_list.get');
Route::post('save_admin', [ApiController::class, 'save_admin'])->name('save_admin.post');
Route::get('delete_admin', [ApiController::class, 'delete_admin'])->name('delete_admin.get');

Route::post('update_status', [ApiController::class, 'update_status'])->name('update_status.post');
