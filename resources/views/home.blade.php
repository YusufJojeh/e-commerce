@extends('layouts.app')

@section('title', $siteName ?? 'Home')

@push('styles')
<style>
  /* Crystal and Glass Effects */
  .crystal-card {
    background: linear-gradient(135deg,
      rgba(255,255,255,0.1) 0%,
      rgba(255,255,255,0.05) 50%,
      rgba(255,255,255,0.02) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.1),
      inset 0 1px 0 rgba(255,255,255,0.2);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .crystal-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
      transparent,
      rgba(255,255,255,0.1),
      transparent);
    transition: left 0.6s ease;
  }

  .crystal-card:hover::before {
    left: 100%;
  }

  .crystal-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.15),
      0 0 0 1px rgba(255,255,255,0.3),
      inset 0 1px 0 rgba(255,255,255,0.3);
  }

  /* Hero Section */
  .hero-section {
    position: relative;
    min-height: 80vh;
    display: flex;
    align-items: center;
    overflow: hidden;
  }

  .hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background:
      radial-gradient(circle at 20% 80%, rgba(240,194,75,0.15) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(120,119,198,0.1) 0%, transparent 50%),
      radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 0%, transparent 50%);
    animation: heroFloat 20s ease-in-out infinite;
  }

  @keyframes heroFloat {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(1deg); }
    66% { transform: translateY(10px) rotate(-1deg); }
  }

  /* Floating Elements */
  .floating-element {
    position: absolute;
    opacity: 0.6;
    animation: float 6s ease-in-out infinite;
  }

  .floating-element:nth-child(2) { animation-delay: -2s; }
  .floating-element:nth-child(3) { animation-delay: -4s; }

  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
  }

  /* Product Cards */
  .product-card {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      rgba(120,119,198,0.1) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(240,194,75,0.3);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.1),
      inset 0 1px 0 rgba(240,194,75,0.2);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
  }

  .product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      transparent 50%,
      rgba(120,119,198,0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .product-card:hover::before {
    opacity: 1;
  }

  .product-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.15),
      0 0 0 1px rgba(240,194,75,0.4),
      inset 0 1px 0 rgba(240,194,75,0.3);
  }

  .product-card .thumb {
    aspect-ratio: 1/1;
    background: var(--surface);
    border-radius: 24px 24px 0 0;
    overflow: hidden;
    position: relative;
  }

  .product-card .thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .product-card:hover .thumb img {
    transform: scale(1.1);
  }

  .product-card .thumb::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg,
      transparent 30%,
      rgba(255,255,255,0.1) 50%,
      transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
  }

  .product-card:hover .thumb::after {
    transform: translateX(100%);
  }

  /* Sale Badge */
  .sale-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 2;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
  }

  /* Category Cards */
  .category-card {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      rgba(120,119,198,0.1) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(240,194,75,0.3);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.1),
      inset 0 1px 0 rgba(240,194,75,0.2);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
  }

  .category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg,
      rgba(240,194,75,0.2) 0%,
      transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .category-card:hover::before {
    opacity: 1;
  }

  .category-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.15),
      0 0 0 1px rgba(240,194,75,0.4),
      inset 0 1px 0 rgba(240,194,75,0.3);
  }

  /* Offer Cards */
  .offer-card {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      rgba(120,119,198,0.1) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(240,194,75,0.3);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.1),
      inset 0 1px 0 rgba(240,194,75,0.2);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
  }

  .offer-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg,
      transparent 30%,
      rgba(240,194,75,0.1) 50%,
      transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
  }

  .offer-card:hover::before {
    transform: translateX(100%);
  }

  .offer-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.15),
      0 0 0 1px rgba(240,194,75,0.4),
      inset 0 1px 0 rgba(240,194,75,0.3);
  }

  /* Section Titles */
  .section-title {
    font-family: 'Georgia', serif;
    font-weight: 700;
    font-size: 2rem;
    background: linear-gradient(135deg, var(--text) 0%, var(--gold) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    margin-bottom: 2rem;
    text-align: center;
  }

  .section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--gold), transparent);
    border-radius: 2px;
  }

  /* Carousel Enhancements */
  .carousel-item {
    border-radius: 24px;
    overflow: hidden;
  }

  .carousel-img {
    height: clamp(300px, 50vw, 600px);
    width: 100%;
    object-fit: cover;
    filter: saturate(1.1) contrast(1.05);
  }

  .carousel-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg,
      rgba(0,0,0,0) 0%,
      rgba(0,0,0,0.3) 50%,
      rgba(0,0,0,0.7) 100%);
  }

  .carousel-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2rem;
    background: linear-gradient(180deg,
      transparent 0%,
      rgba(0,0,0,0.8) 100%);
  }

  /* Stats Section */
  .stats-section {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      rgba(120,119,198,0.1) 100%);
    border-radius: 24px;
    padding: 3rem 0;
    margin: 4rem 0;
  }

  .stat-item {
    text-align: center;
    padding: 1rem;
  }

  .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--gold), #ff6b6b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .stat-label {
    color: var(--muted);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  /* CTA Section */
  .cta-section {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.15) 0%,
      rgba(120,119,198,0.15) 100%);
    border-radius: 24px;
    padding: 4rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
  }

  /* Animations */
  .fade-in-up {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .fade-in-up.visible {
    opacity: 1;
    transform: translateY(0);
  }

  .scale-in {
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .scale-in.visible {
    opacity: 1;
    transform: scale(1);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .hero-section {
      min-height: 60vh;
    }

    .section-title {
      font-size: 1.5rem;
    }

    .stat-number {
      font-size: 2rem;
    }
  }

  /* Dark mode enhancements - Unified Crystal Card Styling */
  html[data-theme="dark"] .crystal-card,
  html[data-theme="dark"] .product-card,
  html[data-theme="dark"] .category-card,
  html[data-theme="dark"] .offer-card {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.15) 0%,
      rgba(120,119,198,0.15) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(240,194,75,0.3);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.3),
      inset 0 1px 0 rgba(240,194,75,0.2);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  html[data-theme="dark"] .crystal-card::before,
  html[data-theme="dark"] .product-card::before,
  html[data-theme="dark"] .category-card::before,
  html[data-theme="dark"] .offer-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
      transparent,
      rgba(240,194,75,0.2),
      transparent);
    transition: left 0.6s ease;
  }

  html[data-theme="dark"] .crystal-card:hover::before,
  html[data-theme="dark"] .product-card:hover::before,
  html[data-theme="dark"] .category-card:hover::before,
  html[data-theme="dark"] .offer-card:hover::before {
    left: 100%;
  }

  html[data-theme="dark"] .crystal-card:hover,
  html[data-theme="dark"] .product-card:hover,
  html[data-theme="dark"] .category-card:hover,
  html[data-theme="dark"] .offer-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.4),
      0 0 0 1px rgba(240,194,75,0.4),
      inset 0 1px 0 rgba(240,194,75,0.3);
  }

  /* Dark mode specific card enhancements */
  html[data-theme="dark"] .product-card .thumb {
    background: rgba(240,194,75,0.1);
    border-radius: 24px 24px 0 0;
  }

  html[data-theme="dark"] .category-card img {
    border-radius: 24px 24px 0 0;
  }

  /* Dark mode sale badge enhancement */
  html[data-theme="dark"] .sale-badge {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    box-shadow: 0 4px 12px rgba(255,107,107,0.3);
  }

  /* Dark mode card body styling */
  html[data-theme="dark"] .product-card .card-body,
  html[data-theme="dark"] .category-card .card-body {
    background: transparent;
    color: var(--text);
  }

  /* Dark mode button enhancements */
  html[data-theme="dark"] .btn-vel-gold {
    background: linear-gradient(135deg, var(--gold), #ffd700);
    border: 1px solid rgba(240,194,75,0.3);
    box-shadow: 0 4px 12px rgba(240,194,75,0.2);
  }

  html[data-theme="dark"] .btn-vel-gold:hover {
    background: linear-gradient(135deg, #ffd700, var(--gold));
    box-shadow: 0 6px 16px rgba(240,194,75,0.3);
    transform: translateY(-2px);
  }
</style>
@endpush

@section('content')

{{-- ====================== HERO SECTION ====================== --}}
@if(isset($visibility['hero']) && $visibility['hero'] && isset($mainSlide))
  <section class="hero-section reveal">
    <div class="hero-bg"></div>

    {{-- Floating Elements --}}
    <div class="floating-element" style="top: 20%; left: 10%; font-size: 2rem;">✨</div>
    <div class="floating-element" style="top: 60%; right: 15%; font-size: 1.5rem;">💎</div>
    <div class="floating-element" style="top: 30%; right: 25%; font-size: 1.8rem;">🌟</div>

    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="crystal-card p-4 p-md-5">
            @if(!empty($mainSlide->title))
              <h1 class="display-4 fw-bold mb-3" style="background: linear-gradient(135deg, var(--text) 0%, var(--gold) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                {{ $mainSlide->title }}
              </h1>
            @endif
            @if(!empty($mainSlide->subtitle))
              <p class="lead mb-4" style="color: var(--muted);">
                {{ $mainSlide->subtitle }}
              </p>
            @endif
            @if(!empty($mainSlide->cta_url))
              <a href="{{ $mainSlide->cta_url }}" class="btn btn-vel-gold btn-lg px-4 py-3">
                {{ $mainSlide->cta_label ?? 'Shop Now' }}
                <i class="ms-2">→</i>
              </a>
            @endif
          </div>
        </div>
        <div class="col-lg-6">
          @if($mainSlide->image_url)
            <div class="crystal-card p-3">
              <img
                src="{{ $mainSlide->image_url }}"
                class="w-100 rounded-4"
                style="height: 400px; object-fit: cover;"
                alt="{{ $mainSlide->title ?? 'Hero' }}"
                loading="eager"
              >
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
          @endif

{{-- ====================== SLIDER SECTION ====================== --}}
@if(isset($visibility['slider']) && $visibility['slider'] && isset($sliderSlides) && $sliderSlides->count())
  <section class="py-5 reveal">
    <div class="container">
      <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          @foreach($sliderSlides as $index => $slide)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                    class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
          @endforeach
        </div>
          <div class="carousel-inner">
          @foreach($sliderSlides as $index => $slide)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
              <img src="{{ $slide->image_url }}" class="carousel-img" alt="{{ $slide->title ?? 'Slide' }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
              <div class="carousel-overlay"></div>
              @if(!empty($slide->title) || !empty($slide->subtitle))
                <div class="carousel-caption">
                  @if(!empty($slide->title))
                    <h3 class="fw-bold">{{ $slide->title }}</h3>
                  @endif
                  @if(!empty($slide->subtitle))
                    <p class="mb-3">{{ $slide->subtitle }}</p>
                @endif
                  @if(!empty($slide->cta_url))
                    <a href="{{ $slide->cta_url }}" class="btn btn-vel-gold">
                      {{ $slide->cta_label ?? 'Learn More' }}
                      </a>
                    @endif
                </div>
              @endif
              </div>
            @endforeach
          </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
      </div>
        </div>
      </section>
    @endif

{{-- ====================== STATS SECTION ====================== --}}
<section class="stats-section reveal">
  <div class="container">
    <div class="row">
      <div class="col-md-3 col-6">
        <div class="stat-item">
          <div class="stat-number">{{ $specialProducts->count() }}+</div>
          <div class="stat-label">Featured Products</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stat-item">
          <div class="stat-number">{{ $categories->count() }}+</div>
          <div class="stat-label">Categories</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stat-item">
          <div class="stat-number">{{ $offers->count() }}+</div>
          <div class="stat-label">Active Offers</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stat-item">
          <div class="stat-number">24/7</div>
          <div class="stat-label">Support</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====================== FEATURED PRODUCTS ====================== --}}
@if(isset($visibility['special']) && $visibility['special'] && isset($specialProducts) && $specialProducts->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="row g-4">
          @foreach($specialProducts as $p)
            <div class="col-6 col-md-3">
              <div class="product-card h-100">
                @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
                  <div class="sale-badge">
                    {{ round((($p->price - $p->sale_price) / $p->price) * 100) }}% OFF
                  </div>
                @endif
                <div class="thumb">
                  <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                  <div class="fw-semibold text-truncate mb-1">{{ $p->name }}</div>
                  @if($p->brand)
                    <div class="small text-muted mb-1">{{ $p->brand->name }}</div>
                  @endif
                  <div class="small mt-auto">
                    @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
                      <span class="text-decoration-line-through text-muted me-1">{{ number_format($p->price, 2) }}</span>
                      <span class="fw-semibold text-success">{{ number_format($p->sale_price, 2) }}</span>
                    @else
                      <span class="fw-semibold">{{ number_format($p->price, 2) }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

  {{-- ====================== CATEGORIES ====================== --}}
@if(isset($visibility['categories']) && $visibility['categories'] && isset($categories) && $categories->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="row g-4">
          @foreach($categories as $cat)
            <div class="col-6 col-md-3">
              <a href="{{ url('/category/' . $cat->slug) }}" class="category-card d-block text-decoration-none h-100">
                @if($cat->image_url)
                  <img src="{{ $cat->image_url }}" class="w-100" style="height: 200px; object-fit: cover;" alt="{{ $cat->name }}" loading="lazy">
                @else
                  <div class="w-100 d-flex align-items-center justify-content-center" style="height: 200px; background: var(--surface);">
                    <span class="text-muted">{{ $cat->name }}</span>
                  </div>
                @endif
                <div class="card-body text-center">
                  <h5 class="fw-semibold mb-0">{{ $cat->name }}</h5>
                </div>
              </a>
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

{{-- ====================== OFFERS ====================== --}}
@if(isset($visibility['offers']) && $visibility['offers'] && isset($offers) && $offers->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">Special Offers</h2>
        <div class="row g-4">
          @foreach($offers as $offer)
            <div class="col-12 col-md-4">
              <div class="offer-card h-100 p-4">
                @if($offer->banner_url)
                  <img src="{{ $offer->banner_url }}" class="w-100 rounded-3 mb-3" style="height: 200px; object-fit: cover;" alt="{{ $offer->title ?? 'Offer' }}" loading="lazy">
                @endif
                <div class="text-center">
                  @if(!empty($offer->title))
                    <h4 class="fw-semibold mb-2">{{ $offer->title }}</h4>
                  @endif
                  @if(!empty($offer->description))
                    <p class="text-muted mb-3">{{ $offer->description }}</p>
                  @endif
                  @if(!empty($offer->cta_url))
                    <a href="{{ $offer->cta_url }}" class="btn btn-vel-gold">
                      Shop Now
                    </a>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

{{-- ====================== NEW ARRIVALS ====================== --}}
@if(isset($visibility['latest']) && $visibility['latest'] && isset($latestProducts) && $latestProducts->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">New Arrivals</h2>
        <div class="row g-4">
          @foreach($latestProducts as $p)
            <div class="col-6 col-md-3">
              <div class="product-card h-100">
                @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
                  <div class="sale-badge">
                    {{ round((($p->price - $p->sale_price) / $p->price) * 100) }}% OFF
                  </div>
                @endif
                <div class="thumb">
                  <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                  <div class="fw-semibold text-truncate mb-1">{{ $p->name }}</div>
                  @if($p->brand)
                    <div class="small text-muted mb-1">{{ $p->brand->name }}</div>
                  @endif
                  <div class="small mt-auto">
                    @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
                      <span class="text-decoration-line-through text-muted me-1">{{ number_format($p->price, 2) }}</span>
                      <span class="fw-semibold text-success">{{ number_format($p->sale_price, 2) }}</span>
                    @else
                      <span class="fw-semibold">{{ number_format($p->price, 2) }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

  {{-- ====================== EXTERNAL BRANDS ====================== --}}
@if(isset($visibility['external']) && $visibility['external'] && isset($externalBrandProducts) && $externalBrandProducts->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">Premium Brands</h2>
        <div class="row g-4">
          @foreach($externalBrandProducts as $p)
            <div class="col-6 col-md-3">
              <div class="product-card h-100">
                @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
                  <div class="sale-badge">
                    {{ round((($p->price - $p->sale_price) / $p->price) * 100) }}% OFF
                  </div>
                @endif
                <div class="thumb">
                  <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}" loading="lazy">
                </div>
                <div class="card-body d-flex flex-column">
                  <div class="fw-semibold text-truncate mb-1">{{ $p->name }}</div>
                  @if($p->brand)
                    <div class="small text-muted mb-1">{{ $p->brand->name }}</div>
                  @endif
                  <div class="small mt-auto">
                    @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
                      <span class="text-decoration-line-through text-muted me-1">{{ number_format($p->price, 2) }}</span>
                      <span class="fw-semibold text-success">{{ number_format($p->sale_price, 2) }}</span>
                    @else
                      <span class="fw-semibold">{{ number_format($p->price, 2) }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

{{-- ====================== CTA SECTION ====================== --}}
<section class="cta-section reveal">
  <div class="container position-relative">
    <h2 class="section-title text-white">Ready to Shop?</h2>
    <p class="lead text-white mb-4">Discover amazing products at unbeatable prices</p>
    <a href="{{ route('products.index') }}" class="btn btn-vel-gold btn-lg px-5 py-3">
      Browse All Products
      <i class="ms-2">→</i>
    </a>
</div>
</section>

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

    // Add staggered animation to product cards
    document.querySelectorAll('.product-card').forEach((card, index) => {
      card.style.animationDelay = `${index * 0.1}s`;
    });
  })();

  // Parallax effect for hero section
  (function(){
    const hero = document.querySelector('.hero-section');
    if (!hero) return;

    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      const rate = scrolled * -0.5;
      hero.style.transform = `translateY(${rate}px)`;
    });
  })();

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
</script>
@endpush
