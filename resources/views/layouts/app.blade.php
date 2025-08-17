<!doctype html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', $siteName ?? 'MyStore')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Velvet Noir base theme --}}
  <style>
    :root{
      --gold:#FFD700;
      --bg:#FAFAFA;
      --surface:#F3F4F6;
      --text:#111216;
      --muted:#6b7280;
      --glass:rgba(255,255,255,.45);
      --glass-strong:rgba(255,255,255,.65);
      --shadow:0 10px 30px rgba(0,0,0,.08);
      --ring:rgba(255,215,0,.28);
    }
    html[data-theme="dark"]{
      --bg:#0B0C0F;
      --surface:#111216;
      --text:#FAFAFA;
      --muted:#9aa0a6;
      --glass:rgba(17,18,22,.55);
      --glass-strong:rgba(17,18,22,.75);
      --shadow:0 10px 30px rgba(0,0,0,.35);
      --ring:rgba(255,215,0,.35);
    }

    body{
      background:
        radial-gradient(1200px 600px at 10% -10%, rgba(255,215,0,.06), transparent 60%),
        radial-gradient(900px 500px at 110% 10%, rgba(255,215,0,.05), transparent 55%),
        var(--bg);
      color:var(--text);
    }

    /* Glassy sticky navbar */
    .vel-nav{
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      background: var(--glass-strong) !important;
      border-bottom: 1px solid var(--ring);
      box-shadow: var(--shadow);
    }
    .navbar-brand, .nav-link { color: var(--text) !important; }
    .nav-link:hover { color: var(--gold) !important; }

    /* Gold buttons globally */
    .btn-vel-gold{
      color:#111216; background:var(--gold); border-color:var(--gold); font-weight:600;
    }
    .btn-vel-gold:hover{ filter:brightness(.96); }
    .btn-vel-outline{
      color:var(--text); border-color:var(--gold);
    }
    .btn-vel-outline:hover{
      background:linear-gradient(90deg, rgba(255,215,0,.15), transparent 70%);
      border-color:var(--gold);
    }

    /* Existing helper */
    .card-img-overlay.bg-dim{
      background:linear-gradient(180deg, rgba(0,0,0,.0), rgba(0,0,0,.45));
    }
  </style>

  @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg vel-nav sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="/">{{ $siteName ?? 'MyStore' }}</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="mainNav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
        {{-- Add more links if you want: categories page, brands page, etc. --}}
      </ul>

      <div class="d-flex align-items-center gap-2">
        {{-- Theme toggle (navbar version) --}}
        <button class="btn btn-sm btn-vel-outline" type="button" id="velToggleNav">
          <span id="velIconNav">🌙</span> Theme
        </button>
      </div>
    </div>
  </div>
</nav>

@yield('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Global theme init (plays nice with page-level toggles) --}}
<script>
  (function () {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    const saved = localStorage.getItem('vel-theme');
    if (!current) {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      html.setAttribute('data-theme', saved || (prefersDark ? 'dark' : 'light'));
    } else if (saved && saved !== current) {
      html.setAttribute('data-theme', saved);
    }
    const reflect = () => {
      const mode = html.getAttribute('data-theme');
      const iconNav = document.getElementById('velIconNav');
      if (iconNav) iconNav.textContent = (mode === 'dark') ? '🌞' : '🌙';
    };
    reflect();

    const toggle = () => {
      const next = (html.getAttribute('data-theme') === 'dark') ? 'light' : 'dark';
      html.setAttribute('data-theme', next);
      localStorage.setItem('vel-theme', next);
      reflect();

      // If the home page floating toggle exists, update its icon too
      const pageIcon = document.getElementById('velIcon');
      if (pageIcon) pageIcon.textContent = (next === 'dark') ? '🌞' : '🌙';
    };

    document.getElementById('velToggleNav')?.addEventListener('click', toggle);
  })();
</script>

@stack('scripts')
</body>
</html>
