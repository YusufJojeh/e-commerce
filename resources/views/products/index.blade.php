@extends('layouts.app')

@section('title', $pageTitle ?? 'Products')

@push('styles')
<style>
  .filters .form-label{font-weight:600}
  .pill{background:var(--glass); border:1px solid var(--border); padding:.35rem .7rem; border-radius:999px; display:inline-flex; gap:.4rem; align-items:center}
  .pill .x{cursor:pointer; opacity:.6}
  .product-card .thumb{aspect-ratio:1/1; background:var(--surface); border-radius:14px; overflow:hidden}
  .product-card .thumb img{width:100%; height:100%; object-fit:cover; transform:scale(1.01); transition:transform .25s ease}
  .product-card:hover .thumb img{transform:scale(1.04)}
  .price-cut{ text-decoration: line-through; opacity:.6 }
</style>
@endpush

@section('content')
@php
  $q         = $q         ?? request('q', '');
  $sort      = $sort      ?? request('sort', 'latest');
  $brand     = request('brand');
  $category  = request('category');
@endphp

<div class="container py-4">

  <h1 class="h4 mb-3">All Products</h1>

  {{-- ================= Filters ================= --}}
  <form method="get" class="row g-2 align-items-end filters mb-3">
    <div class="col-12 col-md-4">
      <label class="form-label">Search</label>
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search products…">
    </div>

    <div class="col-6 col-md-3">
      <label class="form-label">Brand</label>
      <select name="brand" class="form-select">
        <option value="">All</option>
        @foreach($brands as $b)
          <option value="{{ $b->slug }}" @selected($brand===$b->slug)>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-3">
      <label class="form-label">Category</label>
      <select name="category" class="form-select">
        <option value="">All</option>
        @foreach($categories as $c)
          <option value="{{ $c->slug }}" @selected($category===$c->slug)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label">Sort</label>
      <select name="sort" class="form-select">
        <option value="latest"     @selected($sort==='latest')>Latest</option>
        <option value="price_low"  @selected($sort==='price_low')>Price: Low → High</option>
        <option value="price_high" @selected($sort==='price_high')>Price: High → Low</option>
        <option value="name"       @selected($sort==='name')>Name (A–Z)</option>
      </select>
    </div>

    <div class="col-12">
      <button class="btn btn-sm btn-vel-gold">Apply</button>
      <a href="{{ route('products.index') }}" class="btn btn-sm btn-vel-outline">Reset</a>
    </div>
  </form>

  {{-- Active filters pills --}}
  @if($q || $brand || $category)
    <div class="mb-3 d-flex gap-2 flex-wrap">
      @if($q)
        <span class="pill">Search: <strong>{{ $q }}</strong>
          <a class="x text-decoration-none" href="{{ request()->fullUrlWithQuery(['q'=>'']) }}">✕</a>
        </span>
      @endif
      @if($brand)
        <span class="pill">Brand: <strong>{{ $brands->firstWhere('slug',$brand)->name ?? $brand }}</strong>
          <a class="x text-decoration-none" href="{{ request()->fullUrlWithQuery(['brand'=>'']) }}">✕</a>
        </span>
      @endif
      @if($category)
        <span class="pill">Category: <strong>{{ $categories->firstWhere('slug',$category)->name ?? $category }}</strong>
          <a class="x text-decoration-none" href="{{ request()->fullUrlWithQuery(['category'=>'']) }}">✕</a>
        </span>
      @endif
    </div>
  @endif

  {{-- ================= Grid ================= --}}
  <div class="row g-3">
    @forelse($products as $p)
      <div class="col-6 col-md-3">
        @includeIf('partials.product-card', ['p'=>$p])

        @unless(View::exists('partials.product-card'))
          {{-- Fallback card (إن لم يكن الجزئي موجوداً) --}}
          <a href="{{ url('/product/'.$p->slug) }}" class="card product-card h-100 text-decoration-none">
            <div class="thumb">
              <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}" loading="lazy">
            </div>
            <div class="card-body">
              <div class="fw-semibold text-truncate mb-1">{{ $p->name }}</div>
              <div class="small">
                @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
                  <span class="price-cut me-1">{{ number_format($p->price,2) }}</span>
                  <span class="fw-semibold">{{ number_format($p->sale_price,2) }}</span>
                @else
                  <span class="fw-semibold">{{ number_format($p->price,2) }}</span>
                @endif
              </div>
            </div>
          </a>
        @endunless
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info mb-0">No products found.</div>
      </div>
    @endforelse
  </div>

  {{-- ================= Pagination (keeps filters) ================= --}}
  <div class="mt-3">
    {{ $products->appends(request()->query())->links() }}
  </div>
</div>
@endsection
