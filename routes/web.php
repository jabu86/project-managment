<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Models\Product;
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
    return view('layouts.products');
});
Route::get('/', function () {
    $products = Product::latest()->paginate(100);
    return view('all', [
        'products' => $products
    ]);
});
Route::get('/product/{id}', function ($id) {
    $product = Product::find($id);
    return view('product', [
        'product' => $product
    ]);
})->name('product');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/{any?}', [HomeController::class, 'index'])->where('any', '.*');