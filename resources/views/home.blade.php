@extends('layouts.app')

@section('title', $siteName ?? 'Home')

@push('styles')
<style>
  /* حجم صور الكاروسيل + أوفرلاي */
  #homeCarousel .carousel-img{
    height:clamp(260px,42vw,520px);
    width:100%;
    object-fit:cover;
    filter:saturate(1.02) contrast(1.02);
  }
  #homeCarousel .overlay{
    position:absolute; inset:0;
    background:linear-gradient(180deg, rgba(0,0,0,0) 35%, rgba(0,0,0,.55));
  }
  #homeCarousel .caption{
    position:absolute; left:0; right:0; bottom:0;
    padding:1rem 1.25rem;
  }
  .ratio-1x1{ aspect-ratio:1/1; object-fit:cover; }
  .section-title{ font-family: Georgia, "Times New Roman", serif; font-weight:700; letter-spacing:.3px; position:relative; padding-bottom:6px; margin-bottom:14px; display:inline-block; }
  .section-title::after{ content:""; position:absolute; left:0; bottom:0; width:56%; height:3px;
    background:linear-gradient(90deg, var(--gold,#F0C24B), transparent); border-radius:2px; }
</style>
@endpush

@section('content')
<div class="container py-4">

  {{-- ====================== HERO ====================== --}}
  @isset($mainSlide)
    <section class="mb-5 reveal">
      <div class="card border-0 text-white position-relative" style="border-radius:22px; overflow:hidden;">
        <img
          src="{{ asset('storage/' . $mainSlide->image_path) }}"
          class="w-100"
          style="height:clamp(260px,42vw,520px); object-fit:cover; filter:saturate(1.02) contrast(1.02);"
          alt="{{ $mainSlide->title ?? 'Hero' }}"
          loading="eager"
        >
        <div class="position-absolute inset-0" style="inset:0; background:linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,.55));"></div>
        <div class="position-absolute bottom-0 start-0 end-0 p-4 p-md-5">
          <div class="glass text-white p-3 p-md-4">
            @if(!empty($mainSlide->title))
              <h2 class="display-6 mb-2" style="letter-spacing:.3px">{{ $mainSlide->title }}</h2>
            @endif
            @if(!empty($mainSlide->subtitle))
              <p class="mb-3" style="max-width:70ch">{{ $mainSlide->subtitle }}</p>
            @endif
            @if(!empty($mainSlide->cta_url))
              <a href="{{ $mainSlide->cta_url }}" class="btn btn-vel-gold btn-sm px-3"> {{ $mainSlide->cta_label ?? 'Shop Now' }} </a>
            @endif
          </div>
        </div>
      </div>
    </section>
  @endisset

  {{-- ====================== HIGHLIGHTS: CAROUSEL ====================== --}}
  @isset($sliderSlides)
    @if($sliderSlides->count())
      <section class="mb-5 reveal">
        <h2 class="h4 section-title">Highlights</h2>

        <div id="homeCarousel" class="carousel slide glass" data-bs-ride="carousel" data-bs-interval="5000" style="border-radius:22px; overflow:hidden; box-shadow:var(--shadow);">
          {{-- Indicators --}}
          @if($sliderSlides->count() > 1)
            <div class="carousel-indicators">
              @foreach($sliderSlides as $i => $s)
                <button type="button"
                        data-bs-target="#homeCarousel"
                        data-bs-slide-to="{{ $i }}"
                        class="{{ $i === 0 ? 'active' : '' }}"
                        aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $i+1 }}"></button>
              @endforeach
            </div>
          @endif

          {{-- Slides --}}
          <div class="carousel-inner">
            @foreach($sliderSlides as $i => $s)
              <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/'.$s->image_path) }}" class="carousel-img" alt="{{ $s->title ?? 'Slide' }}">
                <div class="overlay"></div>
                <div class="caption">
                  <div class="glass p-3 p-md-4 text-white">
                    @if(!empty($s->title))   <h3 class="h5 mb-1">{{ $s->title }}</h3> @endif
                    @if(!empty($s->subtitle))<p class="mb-2">{{ $s->subtitle }}</p>   @endif
                    @if(!empty($s->cta_url))
                      <a href="{{ $s->cta_url }}" class="btn btn-vel-gold btn-sm"> {{ $s->cta_label ?? 'Shop Now' }} </a>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Controls --}}
          @if($sliderSlides->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          @endif
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== OFFERS ====================== --}}
  @isset($offers)
    @if($offers->count())
      <section class="mb-5 reveal">
        <h2 class="h4 section-title">Offers</h2>
        <div class="row g-3">
          @foreach($offers as $offer)
            <div class="col-12 col-md-4">
              <a href="{{ $offer->cta_url ?? '#' }}" class="card glass h-100 text-decoration-none">
                @if(!empty($offer->banner_image))
                  <img src="{{ asset('storage/' . $offer->banner_image) }}" class="card-img-top ratio-1x1" alt="{{ $offer->title ?? 'Offer' }}" loading="lazy">
                @endif
                <div class="card-body">
                  @if(!empty($offer->title))<div class="fw-semibold">{{ $offer->title }}</div>@endif
                  @if(!empty($offer->description))<p class="small text-muted mb-0">{{ $offer->description }}</p>@endif
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== CATEGORIES ====================== --}}
  @isset($categories)
    @if($categories->count())
      <section class="mb-5 reveal">
        <h2 class="h4 section-title">Categories</h2>
        <div class="row g-3">
          @foreach($categories as $cat)
            <div class="col-6 col-md-3">
              <a href="{{ url('/category/' . $cat->slug) }}" class="card glass card-body text-center h-100 text-decoration-none">
                <span class="fw-semibold">{{ $cat->name }}</span>
              </a>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== SPECIAL (FEATURED) ====================== --}}
  @isset($specialProducts)
    @if($specialProducts->count())
      <section class="mb-5 reveal">
        <h2 class="h4 section-title">Special Picks</h2>
        <div class="row g-3">
          @foreach($specialProducts as $p)
            <div class="col-6 col-md-3">
              @include('partials.product-card', ['p' => $p])
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== LATEST ====================== --}}
  @isset($latestProducts)
    @if($latestProducts->count())
      <section class="mb-5 reveal">
        <h2 class="h4 section-title">New Arrivals</h2>
        <div class="row g-3">
          @foreach($latestProducts as $p)
            <div class="col-6 col-md-3">
              @include('partials.product-card', ['p' => $p])
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== EXTERNAL BRANDS ====================== --}}
  @isset($externalBrandProducts)
    @if($externalBrandProducts->count())
      <section class="reveal">
        <h2 class="h4 section-title">From External Brands</h2>
        <div class="row g-3">
          @foreach($externalBrandProducts as $p)
            <div class="col-6 col-md-3">
              @include('partials.product-card', ['p' => $p])
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

</div>
@endsection

@push('scripts')
<script>
  // كاروسيل: إيقاف عند الهوفر وتشغيل عند الخروج
  (function(){
    const el = document.getElementById('homeCarousel');
    if(!el) return;
    const carousel = bootstrap.Carousel.getOrCreateInstance(el, { interval: 5000, ride: 'carousel' });
    el.addEventListener('mouseenter', () => carousel.pause());
    el.addEventListener('mouseleave', () => carousel.cycle());
  })();

  // Reveal on scroll (لو ما كان موجود سكربت عام في الـ layout)
  (function(){
    if (window.__velRevealBound) return; // تجنّب الازدواج
    window.__velRevealBound = true;
    const io = new IntersectionObserver(entries=>{
      entries.forEach(x=>{ if(x.isIntersecting){ x.target.classList.add('visible'); io.unobserve(x.target); }});
    },{threshold:.12});
    document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
  })();
</script>
@endpush
