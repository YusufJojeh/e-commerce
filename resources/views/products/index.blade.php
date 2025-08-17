@extends('layouts.app')

@section('title', ($pageTitle ?? 'Products'))

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">{{ $pageTitle ?? 'All Products' }}</h1>

  {{-- Filters --}}
  <form method="get" class="row g-2 align-items-end mb-3">
    <div class="col-12 col-md-4">
      <label class="form-label">Search</label>
      <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search products...">
    </div>

    <div class="col-6 col-md-3">
      <label class="form-label">Brand</label>
      <select name="brand" class="form-select">
        <option value="">All</option>
        @foreach($brands as $b)
          <option value="{{ $b->slug }}" @selected(request('brand')===$b->slug)>{{ $b->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-3">
      <label class="form-label">Category</label>
      <select name="category" class="form-select">
        <option value="">All</option>
        @foreach($categories as $c)
          <option value="{{ $c->slug }}" @selected(request('category')===$c->slug)>{{ $c->name }}</option>
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

    <div class="col-12 col-md-12">
      <button class="btn btn-primary">Apply</button>
      <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
  </form>

  {{-- Grid --}}
  <div class="row g-3">
    @forelse($products as $p)
      <div class="col-6 col-md-3">
        @include('partials.product-card', ['p'=>$p])
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info mb-0">No products found.</div>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  <div class="mt-3">
    {{ $products->links() }}
  </div>
</div>
@endsection
