<?php

namespace App\Http\Controllers;

use App\Models\{Category, Product, Offer, Slide, Setting};

class HomeController extends Controller
{
    public function index()
{
    // 1) limits: حوّل JSON إلى مصفوفة + وفّر قيم افتراضية
    $limitsRaw = Setting::get('home.limits'); // سترجع نص JSON
    $limitsArr = is_array($limitsRaw) ? $limitsRaw : (json_decode($limitsRaw ?? '', true) ?: []);
    $limits = array_merge([
        'special'    => 12,
        'latest'     => 12,
        'external'   => 12,
        'categories' => 8,
    ], $limitsArr);

    // 2) باقي البيانات (كما عندك تقريبًا)
    $siteName      = Setting::get('site.name', 'MyStore');
    $mainSlide     = Slide::current()->position('main')->first();
    $sliderSlides  = Slide::current()->position('slider')->take(6)->get();
    $offers        = Offer::current()->orderByDesc('starts_at')->take(3)->get();

    $categories = Category::active()
        ->orderBy('sort_order')
        ->take($limits['categories'])
        ->get();

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
        'siteName','mainSlide','sliderSlides','offers',
        'categories','specialProducts','latestProducts','externalBrandProducts'
    ));
}
}
