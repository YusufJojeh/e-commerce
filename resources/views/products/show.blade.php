@extends('layouts.app')

@section('title', $product->name)

@push('styles')
@include('partials.unified-styles')
<style>
  /* Product Show Page Specific Styles */

  .product-hero {
    background: linear-gradient(135deg, rgba(240,194,75,0.1) 0%, rgba(120,119,198,0.1) 100%);
    border-radius: 24px;
    padding: 3rem 2rem;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
  }

  .product-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
  }

  .product-image-container {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 1rem;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  }

  .product-details {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  }

  .product-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--text) 0%, var(--gold) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
  }

  .product-price {
    font-size: 2rem;
    font-weight: 800;
    color: var(--gold);
    margin-bottom: 1rem;
  }

  .product-original-price {
    font-size: 1.2rem;
    color: var(--muted);
    text-decoration: line-through;
    margin-right: 1rem;
  }

  .product-sale-price {
    font-size: 2rem;
    font-weight: 800;
    color: #10b981;
  }

  .product-meta {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
  }

  .product-description {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  }

  .related-products {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 2rem;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  }
</style>
@endpush

@section('content')

{{-- Product Hero --}}
<section class="product-hero reveal">
  <div class="container position-relative">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="crystal-card p-4 p-md-5">
          <h1 class="product-title">{{ $product->name }}</h1>
          <div class="product-meta">
            @if($product->brand)
              <p class="mb-1"><strong>Brand:</strong>
                <a href="{{ route('brands.show', $product->brand->slug) }}" class="text-decoration-none">{{ $product->brand->name }}</a>
              </p>
            @endif
            <p class="mb-0"><strong>SKU:</strong> <span class="text-monospace">{{ $product->sku }}</span></p>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="product-price text-center">
          @if($product->sale_price)
            <span class="product-original-price">${{ number_format($product->price, 2) }}</span>
            <span class="product-sale-price">${{ number_format($product->sale_price, 2) }}</span>
          @else
            <span class="product-sale-price">${{ number_format($product->price, 2) }}</span>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container py-4">
  {{-- Breadcrumbs --}}
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('common.nav.home') }}</a></li>
      <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('common.nav.products') }}</a></li>
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
      <div class="product-image-container mb-3">
        <img src="{{ $product->primary_image_url }}" class="img-fluid rounded" alt="{{ $product->name }}" loading="eager">
      </div>

      @if($product->images->count() > 1)
        <div class="row g-2">
          @foreach($product->images as $img)
            <div class="col-3">
              <div class="product-image-container">
                <img
                  src="{{ $img->url }}"
                  class="img-fluid rounded"
                  alt="{{ $img->alt ?? $product->name }}"
                  loading="lazy"
                >
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Details --}}
    <div class="col-12 col-md-6">
      <div class="product-details">
        {{-- Short description --}}
        @if($product->short_description)
          <p class="mb-3">{{ $product->short_description }}</p>
        @endif

        {{-- Stock status --}}
        <div class="mb-3">
          <span class="badge {{ $product->stock_qty > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
            {{ $product->stock_qty > 0 ? 'In stock' : 'Out of stock' }}
          </span>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-3">
          <a href="{{ route('products.index') }}" class="btn btn-enhanced">
            <i class="fas fa-arrow-left me-2"></i>{{ __('common.actions.back_to_products') }}
          </a>
          @if($product->stock_qty > 0)
            <button class="btn btn-vel-outline">
              <i class="fas fa-shopping-cart me-2"></i>{{ __('common.actions.add_to_cart') }}
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Full description --}}
  @if(!empty($product->description))
    <div class="mt-5">
      <div class="product-description">
        <h2 class="section-title">{{ __('common.fields.description') }}</h2>
        <div class="content">
          {!! nl2br(e($product->description)) !!}
        </div>
      </div>
    </div>
  @endif

  {{-- Related products --}}
  @if(isset($related) && $related->count())
    <div class="mt-5">
      <div class="related-products">
        <h2 class="section-title">You might also like</h2>
        <div class="row g-4">
          @foreach($related as $p)
            <div class="col-6 col-md-3">
              @include('partials.product-card', ['p' => $p])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  // Enhanced reveal animations
  (function(){
    if (window.__velRevealBound) return;
    window.__velRevealBound = true;

    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Observe all reveal elements
    document.querySelectorAll('.reveal').forEach(el => {
      observer.observe(el);
    });
  })();
</script>
@endpush
