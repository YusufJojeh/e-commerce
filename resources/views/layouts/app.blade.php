<!doctype html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', $siteName ?? 'MyStore')</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- No-FOUC: set theme early (light/dark) --}}
  <script>
    (function () {
      const KEY = 'vel-theme';
      const saved = localStorage.getItem(KEY);
      const theme = (saved === 'light' || saved === 'dark')
        ? saved
        : (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      document.documentElement.setAttribute('data-theme', theme);
    })();
  </script>

  {{-- Dynamic theme variables (served from DB settings). See ThemeController@css --}}
  <link rel="stylesheet" href="{{ route('theme.css') }}?v={{ \App\Models\Setting::get('theme.version','1') }}">

  {{-- Component styles (use CSS vars from theme.css) --}}
  <style>
    body{
      background: var(--bg-grad), var(--bg);
      color: var(--text);
      min-height: 100vh;
      transition: background 0.3s ease, color 0.3s ease;
    }

    html {
      background: var(--bg);
    }

    /* NAVBAR (glassy) */
    .vel-nav{
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      background: var(--glass-strong) !important;
      border-bottom: 1px solid var(--ring);
      box-shadow: var(--shadow);
    }
    .navbar-brand,.nav-link{ color:var(--text)!important; }
    .nav-link:hover{ color:#FFC94D!important; }
    .navbar .form-control{
      background: var(--glass);
      color: var(--text);
      border: 1px solid var(--border);
      border-radius: 999px;
    }
    .navbar .form-control::placeholder{ color:var(--muted); }
    .navbar-toggler{ color:var(--text); border-color:var(--border); }
    .navbar-toggler-icon{
      background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(17,18,22,.85)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
    html[data-theme="dark"] .navbar-toggler-icon{
      background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(236,238,242,.9)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* Buttons */
    .btn-vel-gold{
      background: var(--accent-grad);
      color:#111216;
      border:none; font-weight:600;
      box-shadow: 0 10px 22px rgba(240,194,75,.35);
      transition: transform var(--speed) ease, box-shadow var(--speed) ease, filter var(--speed) ease;
    }
    .btn-vel-gold:hover{ transform:translateY(-2px); filter:brightness(.98); box-shadow:0 14px 28px rgba(240,194,75,.45); }
    .btn-vel-outline{
      color:var(--text); border-color:var(--gold); border-width:1px;
      background:linear-gradient(90deg, rgba(240,194,75,.12), transparent);
      transition:transform var(--speed) ease, box-shadow var(--speed) ease, background var(--speed) ease;
    }
    .btn-vel-outline:hover{
      background:linear-gradient(90deg, rgba(240,194,75,.18), transparent 70%);
      border-color:var(--gold);
      transform:translateY(-2px);
    }

    /* Glass & panels */
    .panel{ background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); }
    .glass{ background:var(--glass)!important; border:1px solid var(--border); border-radius:var(--radius); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); box-shadow:var(--shadow); }

    /* Footer */
    .vel-footer{
      background:
        radial-gradient(800px 400px at 95% 0%, rgba(240,194,75,.06), transparent 60%),
        var(--surface);
      border-top:1px solid var(--border);
    }
    .footer-head{ font-weight:700; margin-bottom:.5rem; }
    .footer-link{ color:var(--text); text-decoration:none; }
    .footer-link:hover{ color:#FFC94D; }

    /* WhatsApp icon & FAB */
    .wa-icon{
      --c:#25D366;
      display:inline-block; width:1em; height:1em; background:var(--c);
      -webkit-mask:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="currentColor" d="M16.04 2.003c-7.711 0-13.977 6.266-13.977 13.977c0 2.463.643 4.872 1.866 6.992L2 30l7.217-1.888a13.91 13.91 0 0 0 6.823 1.768h.001c7.711 0 13.977-6.266 13.977-13.977S23.752 2.003 16.04 2.003zm7.996 19.77c-.34.96-1.97 1.83-2.74 1.86c-.7.03-1.59.04-2.57-.25c-.59-.19-1.35-.44-2.33-.86c-4.11-1.77-6.78-5.93-6.99-6.21c-.2-.28-1.67-2.22-1.67-4.24c0-2.02 1.06-3.01 1.44-3.43c.37-.41.81-.51 1.08-.51c.27 0 .54 0 .78.01c.25.01.58-.09.91.7c.34.82 1.16 2.83 1.26 3.04c.1.21.17.45.03.73c-.13.28-.2.45-.4.69c-.2.24-.42.54-.6.73c-.2.2-.41.42-.18.81c.23.39 1.01 1.66 2.17 2.69c1.49 1.33 2.75 1.74 3.14 1.94c.39.2.62.17.85-.1c.23-.27.98-1.15 1.24-1.54c.26-.39.52-.33.86-.2c.34.14 2.17 1.03 2.54 1.22c.37.19.62.28.71.44c.09.16.09.94-.25 1.9z"/></svg>') center/contain no-repeat;
              mask:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16.04 2.003c-7.711 0-13.977 6.266-13.977 13.977c0 2.463.643 4.872 1.866 6.992L2 30l7.217-1.888a13.91 13.91 0 0 0 6.823 1.768h.001c7.711 0 13.977-6.266 13.977-13.977S23.752 2.003 16.04 2.003zm7.996 19.77c-.34.96-1.97 1.83-2.74 1.86c-.7.03-1.59.04-2.57-.25c-.59-.19-1.35-.44-2.33-.86c-4.11-1.77-6.78-5.93-6.99-6.21c-.2-.28-1.67-2.22-1.67-4.24c0-2.02 1.06-3.01 1.44-3.43c.37-.41.81-.51 1.08-.51c.27 0 .54 0 .78.01c.25.01.58-.09.91.7c.34.82 1.16 2.83 1.26 3.04c.1.21.17.45.03.73c-.13.28-.2.45-.4.69c-.2.24-.42.54-.6.73c-.2.2-.41.42-.18.81c.23.39 1.01 1.66 2.17 2.69c1.49 1.33 2.75 1.74 3.14 1.94c.39.2.62.17.85-.1c.23-.27.98-1.15 1.24-1.54c.26-.39.52-.33.86-.2c.34.14 2.17 1.03 2.54 1.22c.37.19.62.28.71.44c.09.16.09.94-.25 1.9z"/></svg>') center/contain no-repeat;
    }
    .wa-fab{
      position:fixed; right:18px; bottom:18px; z-index:60;
      display:inline-flex; align-items:center; gap:.5rem;
      padding:.6rem .9rem; border-radius:999px; text-decoration:none;
      color:#111; background:var(--glass-strong); border:1px solid var(--border);
      backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px);
      box-shadow:var(--shadow);
      transition:transform var(--speed), box-shadow var(--speed), background var(--speed);
    }
    .wa-fab:hover{ transform:translateY(-3px); background:var(--glass); }

    /* Animations & cards */
    .reveal{ opacity:0; transform:translateY(14px); transition:opacity 600ms ease, transform 600ms ease; }
    .reveal.visible{ opacity:1; transform:translateY(0); }
    .luxe-card{ background:var(--glass); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); transition:transform .28s ease, box-shadow .28s ease; will-change:transform; }
    .luxe-card:hover{ transform:translateY(-6px); box-shadow:0 24px 60px rgba(0,0,0,.16); }
    .luxe-thumb{ aspect-ratio:1/1; border-radius: calc(var(--radius) - 6px); overflow:hidden; background:var(--surface); }
    .luxe-thumb img{ width:100%; height:100%; object-fit:cover; transform:scale(1.01); transition:transform .28s ease, filter .28s ease; }
    .luxe-card:hover .luxe-thumb img{ transform:scale(1.04); filter:saturate(1.04) contrast(1.02); }
  </style>

  @php
    $siteName = $siteName ?? (\App\Models\Setting::get('site.name','MyStore') ?? 'MyStore');
    $logoPath = \App\Models\Setting::get('site.logo_light');
    $favicon  = \App\Models\Setting::get('site.favicon');
  @endphp

  @if($favicon)
    <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
  @endif

  @stack('styles')
</head>
<body>

@php
  $waNumber = \App\Models\Setting::get('site.whatsapp','15551234567');
  $waText   = urlencode('Hello! I need assistance.');
@endphp

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg vel-nav sticky-top">
  <div class="container py-1">
    <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="/">
      @if($logoPath)
        <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $siteName }}" style="height:28px; width:auto;">
      @endif
      <span>{{ $siteName }}</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="mainNav" class="collapse navbar-collapse">
      {{-- Links --}}
      <ul class="navbar-nav me-3 mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Categories</a></li>
      </ul>

      {{-- Search --}}
      <form class="d-flex align-items-center flex-grow-1 me-3" role="search" action="{{ route('products.index') }}" method="get">
        <input class="form-control form-control-sm px-3 me-2" type="search" name="q" placeholder="Search products…" aria-label="Search" value="{{ request('q') }}">
        <button class="btn btn-sm btn-vel-gold" type="submit">Search</button>
      </form>

      {{-- Actions --}}
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-sm btn-vel-outline d-none d-lg-inline-flex" href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener">
          <span class="wa-icon" aria-hidden="true"></span>&nbsp;WhatsApp
        </a>
        <button class="btn btn-sm btn-vel-outline" type="button" id="velToggleNav">
          <span id="velIconNav">🌙</span> Theme
        </button>
      </div>
    </div>
  </div>
</nav>

{{-- PAGE CONTENT --}}
@yield('content')

{{-- FOOTER --}}
<footer class="vel-footer mt-5 pt-5">
  <div class="container pb-4">
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <div class="footer-head">{{ $siteName }}</div>
        <p class="text-muted mb-3" style="max-width:40ch">
          A refined storefront experience with performance, accessibility, and design excellence.
        </p>
        <div class="d-flex align-items-center gap-2">
          <a class="btn btn-sm btn-vel-outline" href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener">
            <span class="wa-icon" aria-hidden="true"></span>&nbsp;Chat on WhatsApp
          </a>
        </div>
      </div>

      <div class="col-6 col-md-2">
        <div class="footer-head">Browse</div>
        <ul class="list-unstyled m-0">
          <li class="mb-2"><a class="footer-link" href="{{ route('products.index') }}">All Products</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2">
        <div class="footer-head">Company</div>
        <ul class="list-unstyled m-0">
          <li class="mb-2"><a class="footer-link" href="#">About</a></li>
          <li class="mb-2"><a class="footer-link" href="#">Contact</a></li>
          <li class="mb-2"><a class="footer-link" href="#">FAQ</a></li>
        </ul>
      </div>

      <div class="col-12 col-md-4">
        <div class="footer-head">Stay in the loop</div>
        <p class="text-muted">Subscribe to updates and product drops.</p>
        <form class="newsletter" onsubmit="event.preventDefault(); alert('Thanks! (wire this to your backend)');">
          <div class="input-group">
            <input type="email" class="form-control form-control-sm px-3" placeholder="Your email" required>
            <button class="btn btn-sm btn-vel-gold px-3" type="submit">Subscribe</button>
          </div>
        </form>
      </div>
    </div>

    <hr class="my-4" style="border-color:var(--border)">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pb-4">
      <div class="small text-muted">&copy; {{ date('Y') }} {{ $siteName }} — All rights reserved.</div>
      <div class="small text-muted mt-2 mt-md-0">
        Crafted with <span style="background:var(--accent-grad); -webkit-background-clip:text; background-clip:text; color:transparent;">Luxe Gradient</span>
      </div>
    </div>
  </div>
</footer>

{{-- Floating WhatsApp FAB --}}
<a class="wa-fab" href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <span class="wa-icon" aria-hidden="true"></span> WhatsApp
</a>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Theme Manager + FX --}}
<script>
(function () {
  const KEY  = 'vel-theme';
  const root = document.documentElement;

  function current() {
    const saved = localStorage.getItem(KEY);
    if (saved === 'light' || saved === 'dark') return saved;
    return matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function syncIcons(theme) {
    const isDark = theme === 'dark';
    const navIcon = document.getElementById('velIconNav');
    if (navIcon) navIcon.textContent = isDark ? '🌞' : '🌙';
  }

  function apply(theme) {
    root.setAttribute('data-theme', theme);
    try { localStorage.setItem(KEY, theme); } catch (_) {}
    syncIcons(theme);
    document.dispatchEvent(new CustomEvent('vel:theme', { detail: { theme } }));
  }

  // init
  apply(current());

  // follow OS if no explicit choice
  try {
    matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      const saved = localStorage.getItem(KEY);
      if (!saved) apply(e.matches ? 'dark' : 'light');
    });
  } catch (_) {}

  // theme toggles
  const toggles = [
    document.getElementById('velToggleNav'),
    ...document.querySelectorAll('[data-theme-toggle]')
  ].filter(Boolean);

  toggles.forEach(btn => btn.addEventListener('click', () => {
    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    apply(next);
  }));

  // pressed effect for gold buttons
  document.addEventListener('mousedown',e=>{
    const btn=e.target.closest('.btn-vel-gold'); if(!btn) return;
    btn.classList.add('pressed');
  });
  document.addEventListener('mouseup',()=>{
    document.querySelectorAll('.btn-vel-gold.pressed').forEach(b=>b.classList.remove('pressed'));
  });

  // subtle tilt for luxe-card
  document.addEventListener('mousemove', e=>{
    document.querySelectorAll('.luxe-card').forEach(card=>{
      const r = card.getBoundingClientRect();
      if(e.clientX<r.left-20 || e.clientX>r.right+20 || e.clientY<r.top-20 || e.clientY>r.bottom+20){
        card.style.transform = ''; // reset (keep hover translate via :hover)
        return;
      }
      const rx = ((e.clientY - r.top)/r.height - .5) * 2; // -1..1
      const ry = ((e.clientX - r.left)/r.width - .5) * 2;
      card.style.transform = `rotateX(${(-rx*2)}deg) rotateY(${(ry*2)}deg) translateY(-6px)`;
    });
  });

  // reveal on scroll
  const io = new IntersectionObserver(entries=>{
    entries.forEach(x=>{ if(x.isIntersecting){ x.target.classList.add('visible'); io.unobserve(x.target); }});
  },{threshold:.12});
  document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
})();
</script>

@stack('scripts')
</body>
</html>
