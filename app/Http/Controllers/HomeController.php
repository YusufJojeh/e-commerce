<?php

namespace App\Http\Controllers;

use App\Services\EnhancedPerformanceService;
use App\Services\PageCacheService;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private EnhancedPerformanceService $performance,
        private PageCacheService $pageCache
    ) {}

    /**
     * Home page with optimized caching
     */
    public function index()
    {
        try {
            // Get cached data for home page components
            $featuredProducts = $this->performance->getCachedFeaturedProducts(8);
            $navigation = $this->performance->getCachedNavigation();
            $siteSettings = $this->performance->getCachedSiteSettings();
            
            // Get latest products
            $latestProducts = $this->performance->getCachedProducts(['sort' => 'latest'], 8, 1);
            
            // Get categories for stats and display
            $categories = $this->performance->getCachedCategories();
            
            // Convert cached arrays to objects for compatibility with views
            $specialProducts = collect($featuredProducts)->map(function ($product) {
                $productObj = (object) $product;
                // Convert nested arrays to objects
                if (isset($product['brand']) && is_array($product['brand'])) {
                    $productObj->brand = (object) $product['brand'];
                }
                if (isset($product['category']) && is_array($product['category'])) {
                    $productObj->category = (object) $product['category'];
                }
                return $productObj;
            });
            $categoriesCollection = collect($categories)->map(function ($category) {
                return (object) $category;
            });
            
            // Get slides for hero and slider sections
            $mainSlide = Slide::position('main')->current()->first();
            $sliderSlides = Slide::position('slider')->current()->orderBy('sort_order')->get();
            
            // Mock data for missing variables
            $offers = collect([]);
            
            $visibility = [
                'hero' => $mainSlide ? true : false,
                'slider' => $sliderSlides->count() > 0,
                'special' => true,
                'categories' => true,
                'offers' => false,
            ];
            
            return view('home', [
                'specialProducts' => $specialProducts,
                'featuredProducts' => $featuredProducts,
                'latestProducts' => $latestProducts['data'],
                'navigation' => $navigation,
                'settings' => $siteSettings,
                'categories' => $categoriesCollection,
                'offers' => $offers,
                'mainSlide' => $mainSlide,
                'sliderSlides' => $sliderSlides,
                'visibility' => $visibility,
                'siteName' => $siteSettings['site_name'] ?? 'E-Commerce Store',
            ]);
        } catch (\Exception $e) {
            // Fallback to basic data if caching fails
            $mainSlide = Slide::position('main')->current()->first();
            $sliderSlides = Slide::position('slider')->current()->orderBy('sort_order')->get();
            
            return view('home', [
                'specialProducts' => collect([]),
                'featuredProducts' => [],
                'latestProducts' => ['data' => []],
                'navigation' => ['categories' => [], 'brands' => []],
                'settings' => [],
                'categories' => collect([]),
                'offers' => collect([]),
                'mainSlide' => $mainSlide,
                'sliderSlides' => $sliderSlides,
                'visibility' => [
                    'hero' => $mainSlide ? true : false,
                    'slider' => $sliderSlides->count() > 0,
                    'special' => false,
                    'categories' => false,
                    'offers' => false,
                ],
                'siteName' => 'E-Commerce Store',
            ]);
        }
    }

    /**
     * Search with optimized caching
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $page = $request->input('page', 1);
        
        if (empty($query)) {
            return redirect()->route('products.index');
        }

        $filters = [
            'search' => $query,
            'sort' => $request->input('sort', 'relevance'),
        ];

        // Use cached search results
        $result = $this->performance->getCachedProducts($filters, 12, $page);
        
        // Get search suggestions
        $suggestions = $this->performance->getCachedSearchSuggestions($query, 10);
        
        // Get cached navigation for filters
        $navigation = $this->performance->getCachedNavigation();

        return view('search.results', [
            'products' => $result['data'],
            'pagination' => $result['pagination'],
            'query' => $query,
            'suggestions' => $suggestions,
            'brands' => $navigation['brands'],
            'categories' => $navigation['categories'],
        ]);
    }

    /**
     * Get search suggestions via AJAX
     */
    public function searchSuggestions(Request $request)
    {
        $query = trim($request->input('q', ''));
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = $this->performance->getCachedSearchSuggestions($query, 8);
        
        return response()->json($suggestions);
    }

    /**
     * About page with caching
     */
    public function about()
    {
        $content = $this->pageCache->cacheFragment('about_page', function () {
            $siteSettings = $this->performance->getCachedSiteSettings();
            return view('pages.about', ['settings' => $siteSettings])->render();
        }, 86400); // Cache for 24 hours

        return response($content)
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Contact page with caching
     */
    public function contact()
    {
        $content = $this->pageCache->cacheFragment('contact_page', function () {
            $siteSettings = $this->performance->getCachedSiteSettings();
            return view('pages.contact', ['settings' => $siteSettings])->render();
        }, 86400); // Cache for 24 hours

        return response($content)
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Privacy policy page with caching
     */
    public function privacy()
    {
        $content = $this->pageCache->cacheFragment('privacy_page', function () {
            $siteSettings = $this->performance->getCachedSiteSettings();
            return view('pages.privacy', ['settings' => $siteSettings])->render();
        }, 86400); // Cache for 24 hours

        return response($content)
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Terms of service page with caching
     */
    public function terms()
    {
        $content = $this->pageCache->cacheFragment('terms_page', function () {
            $siteSettings = $this->performance->getCachedSiteSettings();
            return view('pages.terms', ['settings' => $siteSettings])->render();
        }, 86400); // Cache for 24 hours

        return response($content)
            ->header('Cache-Control', 'public, max-age=86400');
    }
}