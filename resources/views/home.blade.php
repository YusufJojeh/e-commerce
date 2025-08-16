@extends('layouts.app')

@section('content')
<div class="container py-4">

  {{-- Main Hero --}}
  @if($mainSlide)
  <section class="mb-5">
    <div class="card border-0 text-white">
      <img src="{{ asset('storage/'.$mainSlide->image_path) }}" class="card-img" alt="{{ $mainSlide->title }}">
      <div class="card-img-overlay d-flex flex-column justify-content-end p-4 bg-dim">
        @if($mainSlide->title)<h2 class="h3 mb-1">{{ $mainSlide->title }}</h2>@endif
        @if($mainSlide->subtitle)<p class="mb-3">{{ $mainSlide->subtitle }}</p>@endif
        @if($mainSlide->cta_url)
          <a href="{{ $mainSlide->cta_url }}" class="btn btn-light btn-sm">{{ $mainSlide->cta_label ?? 'Shop Now' }}</a>
        @endif
      </div>
    </div>
  </section>
  @endif

  {{-- Slider --}}
  @if($slider->count())
  <section class="mb-5">
    <div class="row g-3">
      @foreach($slider as $s)
      <div class="col-6 col-md-3">
        <a class="card h-100 text-decoration-none" href="{{ $s->cta_url ?? '#' }}">
          <img src="{{ asset('storage/'.$s->image_path) }}" class="card-img-top" alt="{{ $s->title }}">
          <div class="card-body">
            <div class="fw-semibold small">{{ $s->title }}</div>
            @if($s->subtitle)<div class="small text-muted">{{ $s->subtitle }}</div>@endif
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- Offers --}}
  @if($offers->count())
  <section class="mb-5">
    <h2 class="h4 mb-3">Offers</h2>
    <div class="row g-3">
      @foreach($offers as $offer)
      <div class="col-12 col-md-4">
        <a href="{{ $offer->cta_url ?? '#' }}" class="card h-100 text-decoration-none">
          @if($offer->banner_image)
            <img src="{{ asset('storage/'.$offer->banner_image) }}" class="card-img-top" alt="{{ $offer->title }}">
          @endif
          <div class="card-body">
            <div class="fw-semibold">{{ $offer->title }}</div>
            @if($offer->description)<p class="small text-muted mb-0">{{ $offer->description }}</p>@endif
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- Categories (always shown on Home) --}}
  @if($categories->count())
  <section class="mb-5">
    <h2 class="h4 mb-3">Categories</h2>
    <div class="row g-3">
      @foreach($categories as $cat)
      <div class="col-6 col-md-3">
        <a href="#" class="card card-body text-center h-100 text-decoration-none">
          <span class="fw-semibold">{{ $cat->name }}</span>
        </a>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- Special (Featured) --}}
  @if($specialProducts->count())
  <section class="mb-5">
    <h2 class="h4 mb-3">Special Picks</h2>
    <div class="row g-3">
      @foreach($specialProducts as $p)
        <div class="col-6 col-md-3">@include('partials.product-card',['p'=>$p])</div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- Latest --}}
  @if($latestProducts->count())
  <section class="mb-5">
    <h2 class="h4 mb-3">New Arrivals</h2>
    <div class="row g-3">
      @foreach($latestProducts as $p)
        <div class="col-6 col-md-3">@include('partials.product-card',['p'=>$p])</div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- External Brands --}}
  @if($externalBrandProducts->count())
  <section>
    <h2 class="h4 mb-3">From External Brands</h2>
    <div class="row g-3">
      @foreach($externalBrandProducts as $p)
        <div class="col-6 col-md-3">@include('partials.product-card',['p'=>$p])</div>
      @endforeach
    </div>
  </section>
  @endif

</div>
@endsection
