<?php

namespace App\Http\Controllers;

use App\Models\{Category, Product, Offer, Slide, Setting};

class HomeController extends Controller
{
    protected $visibilityController;

    public function __construct()
    {
        $this->visibilityController = new HomeVisibilityController();
    }

    public function index()
    {
        // Get visibility settings and limits from the controller
        $visibility = $this->visibilityController->getVisibilitySettings();
        $limits = $this->visibilityController->getSectionLimits();

        // 2) باقي البيانات (كما عندك تقريبًا)
        $siteName      = Setting::get('site.name', 'MyStore');
        $mainSlide     = Slide::current()->position('main')->first();
        $sliderSlides  = Slide::current()->position('slider')->take($limits['slider'])->get();
        $offers        = Offer::current()->orderByDesc('starts_at')->take(3)->get();

        $categories = Category::active()
            ->orderBy('sort_order')
            ->take($limits['categories'])
            ->get();

        $specialProducts = Product::active()->featured()
            ->with(['images','category','brand'])
            ->latest('published_at')
            ->take($limits['special'])
            ->get();

        $latestProducts = Product::active()
            ->with(['images','category','brand'])
            ->latest('published_at')
            ->take($limits['latest'])
            ->get();

        $externalBrandProducts = Product::active()->externalBrand()
            ->with(['images','category','brand'])
            ->latest('published_at')
            ->take($limits['external'])
            ->get();

        return view('home', compact(
            'siteName','mainSlide','sliderSlides','offers',
            'categories','specialProducts','latestProducts','externalBrandProducts',
            'visibility', 'limits'
        ));
    }
}
