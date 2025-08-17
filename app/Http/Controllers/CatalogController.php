<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    // /products — قائمة مع بحث وفرز وتصفية خفيفة
    public function index(Request $request)
    {
        $q        = trim((string) $request->input('q', ''));
        $brand    = $request->input('brand');
        $category = $request->input('category');
        $sort     = $request->input('sort', 'latest'); // latest | price_low | price_high | name

        $products = Product::active()
            ->with(['primaryImage','brand','category'])
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('short_description', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->when($brand, function ($qq) use ($brand) {
                $qq->whereHas('brand', fn($b) => $b->where('slug', $brand));
            })
            ->when($category, function ($qq) use ($category) {
                $qq->whereHas('category', fn($c) => $c->where('slug', $category));
            });

        // الفرز
        switch ($sort) {
            case 'price_low':  $products->orderBy('sale_price')->orderBy('price'); break;
            case 'price_high': $products->orderByDesc('sale_price')->orderByDesc('price'); break;
            case 'name':       $products->orderBy('name'); break;
            default:           $products->latest('published_at'); // latest
        }

        $products = $products->paginate(12)->withQueryString();

        // لعناصر الفلترة الجانبية/العليا
        $brands     = Brand::where('is_active',1)->orderBy('name')->get(['name','slug']);
        $categories = Category::active()->orderBy('sort_order')->get(['name','slug']);

        return view('products.index', compact('products','brands','categories','q','brand','category','sort'));
    }

    // /product/{slug} — سنكملها في الخطوة التالية
    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['images','brand','category'])
            ->where('slug', $slug)
            ->firstOrFail();

        // سنعيد هنا view للمنتج في الخطوة القادمة
        return view('products.show', compact('product'));
    }

    // /category/{slug} — سنفصلها بالخطوة القادمة
    public function category(string $slug, Request $request)
    {
        $category = Category::active()->where('slug',$slug)->firstOrFail();

        // تضمين أبناء التصنيف (مستوى واحد) إن وجِد
        $childIds = $category->children()->pluck('id');
        $catIds   = collect([$category->id])->merge($childIds);

        $products = Product::active()
            ->with(['primaryImage','brand','category'])
            ->whereIn('category_id', $catIds)
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products'   => $products,
            'brands'     => Brand::where('is_active',1)->orderBy('name')->get(['name','slug']),
            'categories' => Category::active()->orderBy('sort_order')->get(['name','slug']),
            'q'          => '',
            'brand'      => null,
            'category'   => $slug,
            'sort'       => 'latest',
            'pageTitle'  => 'Category: '.$category->name,
        ]);
    }

    // /brand/{slug} — سنفصلها بالخطوة القادمة
    public function brand(string $slug, Request $request)
    {
        $brand = Brand::where('is_active',1)->where('slug',$slug)->firstOrFail();

        $products = Product::active()
            ->with(['primaryImage','brand','category'])
            ->where('brand_id', $brand->id)
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products'   => $products,
            'brands'     => Brand::where('is_active',1)->orderBy('name')->get(['name','slug']),
            'categories' => Category::active()->orderBy('sort_order')->get(['name','slug']),
            'q'          => '',
            'brand'      => $slug,
            'category'   => null,
            'sort'       => 'latest',
            'pageTitle'  => 'Brand: '.$brand->name,
        ]);
    }
}
