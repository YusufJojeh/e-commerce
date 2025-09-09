<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeVisibilityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ThemeCssController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [CatalogController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/wishlist', [CatalogController::class, 'wishlist'])->name('wishlist.index');

Route::get('/categories', [CatalogController::class, 'categories'])->name('categories.index');
Route::get('/category/{slug}', [CatalogController::class, 'category'])->name('categories.show');
Route::get('/brand/{slug}', [CatalogController::class, 'brand'])->name('brands.show');

// Additional routes
Route::get('/brands', [CatalogController::class, 'brands'])->name('brands.index');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Additional legal pages
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');
Route::get('/terms-of-service', function () {
    return view('terms-of-service');
})->name('terms-of-service');

Route::get('/theme.css', ThemeCssController::class)->name('theme.css');

// Home Visibility API Routes
Route::prefix('api/home')->group(function () {
    Route::get('/settings', [HomeVisibilityController::class, 'getAllSettings'])->name('api.home.settings');
    Route::post('/visibility/update', [HomeVisibilityController::class, 'updateVisibility'])->name('api.home.visibility.update');
    Route::post('/limits/update', [HomeVisibilityController::class, 'updateLimits'])->name('api.home.limits.update');
    Route::post('/visibility/bulk', [HomeVisibilityController::class, 'bulkUpdateVisibility'])->name('api.home.visibility.bulk');
    Route::post('/visibility/toggle/{section}', [HomeVisibilityController::class, 'toggleVisibility'])->name('api.home.visibility.toggle');
});

use Illuminate\Support\Facades\Redis;

Route::get('/redis-test', function () {
    Redis::set('framework', 'Laravel with Predis');
    return Redis::get('framework');
});
