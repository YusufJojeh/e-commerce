@extends('layouts.app')

@section('title', $siteName ?? 'Home')

@section('content')

{{-- ========= Velvet Noir styles (scoped) ========= --}}
<style>
  /* Theme tokens */
  :root {
    --gold: #FFD700;
    --bg: #FAFAFA;
    --surface: #F3F4F6;
    --text: #111216;
    --muted: #6b7280;
    --glass: rgba(255, 255, 255, 0.45);
    --glass-strong: rgba(255, 255, 255, 0.65);
    --shadow: 0 10px 30px rgba(0,0,0,.08);
    --ring: rgba(255, 215, 0, 0.28);
  }
  html[data-theme="dark"] {
    --bg: #0B0C0F;
    --surface: #111216;
    --text: #FAFAFA;
    --muted: #9aa0a6;
    --glass: rgba(17, 18, 22, 0.55);
    --glass-strong: rgba(17, 18, 22, 0.75);
    --shadow: 0 10px 30px rgba(0,0,0,.35);
    --ring: rgba(255, 215, 0, 0.35);
  }

  body {
    background: radial-gradient(1200px 600px at 10% -10%, rgba(255,215,0,.06), transparent 60%),
                radial-gradient(900px 500px at 110% 10%, rgba(255,215,0,.05), transparent 55%),
                var(--bg);
    color: var(--text);
  }

  /* Reusable tokens */
  .vel-section-title {
    letter-spacing: .3px;
    position: relative;
    display: inline-block;
  }
  .vel-section-title:after {
    content: "";
    position: absolute;
    left: 0; bottom: -6px;
    width: 40%;
    height: 2px;
    background: linear-gradient(90deg, var(--gold), transparent);
    border-radius: 999px;
  }

  /* Glass cards */
  .vel-glass {
    background: var(--glass);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, .18);
    border-color: var(--ring);
    box-shadow: var(--shadow);
    transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
  }
  .vel-hover-lift:hover { transform: translateY(-4px); }
  .vel-hover-ring:hover { border-color: rgba(255, 215, 0, .6); box-shadow: 0 12px 40px rgba(255, 215, 0, .15); }

  /* Hero */
  .vel-hero {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    min-height: 360px;
  }
  .vel-hero-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transform: scale(1.02);
    transition: transform 1.2s ease;
  }
  .vel-hero:hover .vel-hero-img { transform: scale(1.06); }
  .vel-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,.05), rgba(0,0,0,.35));
  }
  html[data-theme="dark"] .vel-hero-overlay {
    background: linear-gradient(180deg, rgba(0,0,0,.25), rgba(0,0,0,.6));
  }
  .vel-hero-content {
    position: absolute; inset: 0;
    display: flex; align-items: end;
    padding: 1.25rem;
  }
  .vel-hero-chip {
    display: inline-flex; align-items: center; gap: .5rem;
    font-weight: 600; font-size: .85rem;
    color: #111216;
    background: var(--gold);
    padding: .35rem .7rem;
    border-radius: 999px;
    box-shadow: 0 6px 16px rgba(255,215,0,.25);
    margin-bottom: .5rem;
  }

  /* Buttons */
  .btn-vel-gold {
    color: #111216; background: var(--gold); border-color: var(--gold);
    font-weight: 600;
  }
  .btn-vel-gold:hover { filter: brightness(.96); }
  .btn-vel-outline {
    color: var(--text); border-color: var(--gold);
  }
  .btn-vel-outline:hover {
    background: linear-gradient(90deg, rgba(255,215,0,.15), transparent 70%);
    border-color: var(--gold);
  }

  /* Product / Offer / Slider cards */
  .vel-card .card {
    border: 0;
    color: inherit;
    background: var(--glass);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, .18);
    border-color: rgba(255,215,0,.22);
    box-shadow: var(--shadow);
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }
  .vel-card .card:hover {
    transform: translateY(-5px);
    border-color: rgba(255,215,0,.55);
    box-shadow: 0 14px 40px rgba(255,215,0,.12);
  }
  .vel-pill {
    display:inline-block; font-size:.75rem; opacity:.9; color:var(--muted);
  }

  /* Slider thumbnails keep aspect */
  .vel-thumb { aspect-ratio: 4 / 3; object-fit: cover; }

  /* Fade-up animation */
  .fade-up { opacity: 0; transform: translateY(10px); }
  .inview { opacity: 1; transform: translateY(0); transition: opacity .55s ease, transform .55s ease; }

  /* Theme toggle (floating) */
  .vel-toggle {
    position: fixed; right: 18px; bottom: 18px; z-index: 9999;
    border-radius: 999px; border: 1px solid var(--ring);
    background: var(--glass-strong); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    box-shadow: var(--shadow);
  }
  .vel-toggle button {
    border: 0; background: transparent; color: var(--text);
    padding: .6rem .85rem; font-weight: 600;
  }
  .vel-toggle button:hover { color: var(--gold); }
</style>

<div class="container py-4">

  {{-- ====================== Main Hero ====================== --}}
  @if(!empty($mainSlide))
    <section class="mb-5 fade-up">
      <div class="vel-hero vel-glass vel-hover-ring">
        <img
          src="{{ asset('storage/' . $mainSlide->image_path) }}"
          class="vel-hero-img"
          alt="{{ $mainSlide->title ?? 'Hero' }}"
          loading="eager"
        >
        <div class="vel-hero-overlay"></div>

        <div class="vel-hero-content">
          <div class="text-white">
            <div class="vel-hero-chip">Velvet Noir</div>
            @if(!empty($mainSlide->title))
              <h2 class="display-6 fw-bold mb-1" style="text-shadow: 0 4px 24px rgba(0,0,0,.4)">{{ $mainSlide->title }}</h2>
            @endif
            @if(!empty($mainSlide->subtitle))
              <p class="mb-3" style="opacity:.95">{{ $mainSlide->subtitle }}</p>
            @endif
            <div class="d-flex gap-2">
              @if(!empty($mainSlide->cta_url))
                <a href="{{ $mainSlide->cta_url }}" class="btn btn-vel-gold btn-sm">{{ $mainSlide->cta_label ?? 'Shop Now' }}</a>
              @endif
              <a href="{{ route('products.index') }}" class="btn btn-vel-outline btn-sm">Browse All</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif

  {{-- ====================== Slider ====================== --}}
  @isset($sliderSlides)
    @if($sliderSlides->count())
      <section class="mb-5 fade-up">
        <h2 class="h4 vel-section-title mb-3">Highlights</h2>
        <div class="row g-3 vel-card">
          @foreach($sliderSlides as $s)
            <div class="col-6 col-md-3">
              <a class="card h-100 text-decoration-none vel-hover-lift" href="{{ $s->cta_url ?? '#' }}">
                <img
                  src="{{ asset('storage/' . $s->image_path) }}"
                  class="card-img-top vel-thumb"
                  alt="{{ $s->title ?? 'Slide' }}"
                  loading="lazy"
                >
                <div class="card-body">
                  @if(!empty($s->title))
                    <div class="fw-semibold small">{{ $s->title }}</div>
                  @endif
                  @if(!empty($s->subtitle))
                    <div class="vel-pill">{{ $s->subtitle }}</div>
                  @endif
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== Offers ====================== --}}
  @isset($offers)
    @if($offers->count())
      <section class="mb-5 fade-up">
        <h2 class="h4 vel-section-title mb-3">Offers</h2>
        <div class="row g-3 vel-card">
          @foreach($offers as $offer)
            <div class="col-12 col-md-4">
              <a href="{{ $offer->cta_url ?? '#' }}" class="card h-100 text-decoration-none vel-hover-lift">
                @if(!empty($offer->banner_image))
                  <img
                    src="{{ asset('storage/' . $offer->banner_image) }}"
                    class="card-img-top"
                    alt="{{ $offer->title ?? 'Offer' }}"
                    loading="lazy"
                  >
                @endif
                <div class="card-body">
                  @if(!empty($offer->title))
                    <div class="fw-semibold">{{ $offer->title }}</div>
                  @endif
                  @if(!empty($offer->description))
                    <p class="small text-muted mb-0">{{ $offer->description }}</p>
                  @endif
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== Categories ====================== --}}
  @isset($categories)
    @if($categories->count())
      <section class="mb-5 fade-up">
        <h2 class="h4 vel-section-title mb-3">Categories</h2>
        <div class="row g-3">
          @foreach($categories as $cat)
            <div class="col-6 col-md-3">
              <a href="{{ url('/category/' . $cat->slug) }}"
                 class="card card-body text-center h-100 text-decoration-none vel-glass vel-hover-lift vel-hover-ring">
                <span class="fw-semibold">{{ $cat->name }}</span>
              </a>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== Special (Featured) ====================== --}}
  @isset($specialProducts)
    @if($specialProducts->count())
      <section class="mb-5 fade-up">
        <h2 class="h4 vel-section-title mb-3">Special Picks</h2>
        <div class="row g-3 product-grid">
          @foreach($specialProducts as $p)
            <div class="col-6 col-md-3">
              {{-- Your partial renders a Bootstrap .card; we glass it up via wrapper styles --}}
              <div class="vel-card">@include('partials.product-card', ['p' => $p])</div>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== Latest ====================== --}}
  @isset($latestProducts)
    @if($latestProducts->count())
      <section class="mb-5 fade-up">
        <h2 class="h4 vel-section-title mb-3">New Arrivals</h2>
        <div class="row g-3 product-grid">
          @foreach($latestProducts as $p)
            <div class="col-6 col-md-3">
              <div class="vel-card">@include('partials.product-card', ['p' => $p])</div>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

  {{-- ====================== External Brands ====================== --}}
  @isset($externalBrandProducts)
    @if($externalBrandProducts->count())
      <section class="fade-up">
        <h2 class="h4 vel-section-title mb-3">From External Brands</h2>
        <div class="row g-3 product-grid">
          @foreach($externalBrandProducts as $p)
            <div class="col-6 col-md-3">
              <div class="vel-card">@include('partials.product-card', ['p' => $p])</div>
            </div>
          @endforeach
        </div>
      </section>
    @endif
  @endisset

</div>

{{-- Floating theme toggle --}}
<div class="vel-toggle vel-glass">
  <button type="button" id="velToggle" aria-label="Toggle theme">
    <span id="velIcon">🌙</span> Theme
  </button>
</div>

{{-- ========= Velvet Noir scripts (tiny, scoped) ========= --}}
<script>
  // Theme init: prefers-color-scheme + storage
  (function () {
    const saved = localStorage.getItem('vel-theme');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const html = document.documentElement;
    const apply = (mode) => {
      html.setAttribute('data-theme', mode);
      document.getElementById('velIcon')?.innerText = mode === 'dark' ? '🌞' : '🌙';
    };
    apply(saved || (prefersDark ? 'dark' : 'light'));
    document.getElementById('velToggle')?.addEventListener('click', () => {
      const next = (html.getAttribute('data-theme') === 'dark') ? 'light' : 'dark';
      apply(next);
      localStorage.setItem('vel-theme', next);
    });
  })();

  // Fade-up on in-view
  (function () {
    const els = Array.from(document.querySelectorAll('.fade-up'));
    if (!('IntersectionObserver' in window) || !els.length) {
      els.forEach(el => el.classList.add('inview'));
      return;
    }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          e.target.classList.add('inview');
          io.unobserve(e.target);
        }
      });
    }, { rootMargin: '0px 0px -5% 0px', threshold: .08 });
    els.forEach(el => io.observe(el));
  })();
</script>

@endsection
