<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/productList', [ProductController::class, 'index']);
Route::post('/productList/all', [ProductController::class, 'getAllProducts']);
Route::post('/product/delete/{id}', [ProductController::class, 'destroy']);

Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
// Route::get('/productList/delete', [ProductController::class, 'delete']);

//Route::resource('product', ProductController::class);

