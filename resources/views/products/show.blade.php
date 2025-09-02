@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
  {{-- Breadcrumbs --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
      @if($product->category)
        <li class="breadcrumb-item">
          <a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a>
        </li>
      @endif
      <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
  </nav>

  <div class="row g-4">
    {{-- Images --}}
    <div class="col-12 col-md-6">
      <div class="card mb-3">
        <img src="{{ $product->primary_image_url }}" class="card-img-top" alt="{{ $product->name }}" loading="eager">
      </div>

      @if($product->images->count() > 1)
        <div class="row g-2">
          @foreach($product->images as $img)
            <div class="col-3">
              <img
                src="{{ $img->url }}"
                class="img-fluid rounded border"
                alt="{{ $img->alt ?? $product->name }}"
                loading="lazy"
              >
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Details --}}
    <div class="col-12 col-md-6">
      <h1 class="h4 mb-2">{{ $product->name }}</h1>

      <div class="small text-muted mb-2">
        @if($product->brand)
          Brand:
          <a href="{{ route('brands.show', $product->brand->slug) }}">{{ $product->brand->name }}</a>
          &nbsp;•&nbsp;
        @endif
        SKU: <span class="text-monospace">{{ $product->sku }}</span>
      </div>

      {{-- Price --}}
      <div class="mb-3">
        @if($product->sale_price)
          <div class="fs-4 fw-bold d-inline-block me-2">{{ number_format($product->sale_price, 2) }}</div>
          <div class="text-muted text-decoration-line-through d-inline-block">{{ number_format($product->price, 2) }}</div>
        @else
          <div class="fs-4 fw-bold">{{ number_format($product->price, 2) }}</div>
        @endif
      </div>

      {{-- Short description --}}
      @if($product->short_description)
        <p class="mb-3">{{ $product->short_description }}</p>
      @endif

      {{-- Stock status --}}
      <p class="small {{ $product->stock_qty > 0 ? 'text-success' : 'text-danger' }}">
        {{ $product->stock_qty > 0 ? 'In stock' : 'Out of stock' }}
      </p>
    </div>
  </div>

  {{-- Full description --}}
  @if(!empty($product->description))
    <div class="mt-4">
      <h2 class="h5">Description</h2>
      <div class="card card-body">
        {!! nl2br(e($product->description)) !!}
      </div>
    </div>
  @endif

  {{-- Related products --}}
  @if(isset($related) && $related->count())
    <div class="mt-5">
      <h2 class="h5 mb-3">You might also like</h2>
      <div class="row g-3">
        @foreach($related as $p)
          <div class="col-6 col-md-3">
            @include('partials.product-card', ['p' => $p])
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>
@endsection
