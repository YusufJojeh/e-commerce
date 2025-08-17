<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/products', [CatalogController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [CatalogController::class, 'show'])->name('products.show');

Route::get('/category/{slug}', [CatalogController::class, 'category'])->name('categories.show');
Route::get('/brand/{slug}', [CatalogController::class, 'brand'])->name('brands.show');
