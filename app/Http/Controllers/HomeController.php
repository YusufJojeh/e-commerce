<?php

namespace App\Http\Controllers;

use App\Models\{Category, Product, Offer, Slide, Setting};

class HomeController extends Controller
{
    public function index()
    {
        // Limits (fallbacks if setting not found)
        $limits = Setting::get('home.limits', [
            'special' => 12, 'latest' => 12, 'external' => 12, 'categories' => 8
        ]);

        // Site name for <title> / header
        $siteName = Setting::get('site.name', 'MyStore');

        // Slides
        $mainSlide = Slide::current()->position('main')->orderBy('sort_order')->first();
        $slider    = Slide::current()->position('slider')->orderBy('sort_order')->take(6)->get();

        // Offers (time-windowed)
        $offers = Offer::current()->orderByDesc('starts_at')->take(3)->get();

        // Categories (always shown on Home)
        $categories = Category::active()->orderBy('sort_order')->take($limits['categories'])->get();

        // Products (status-driven)
        $specialProducts = Product::active()->featured()
            ->with(['primaryImage','category','brand'])
            ->latest('published_at')
            ->take($limits['special'])
            ->get();

        $latestProducts = Product::active()
            ->with(['primaryImage','category','brand'])
            ->latest('published_at')
            ->take($limits['latest'])
            ->get();

        $externalBrandProducts = Product::active()->externalBrand()
            ->with(['primaryImage','category','brand'])
            ->latest('published_at')
            ->take($limits['external'])
            ->get();

        return view('home', compact(
            'siteName','mainSlide','slider','offers','categories',
            'specialProducts','latestProducts','externalBrandProducts'
        ));
    }
}
