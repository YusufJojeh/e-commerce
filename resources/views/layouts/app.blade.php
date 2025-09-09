<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ $htmlDir ?? 'ltr' }}" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', $siteName ?? 'AVENUE')</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  {{-- Brand Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Montserrat:wght@500;700&display=swap" rel="stylesheet">

  {{-- Font Awesome --}}
  <link rel="preload" as="image" href="{{ asset('brand/avenue.svg') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous">

  {{-- CSS Fallback System --}}
  <script>
    // CSS Fallback for Bootstrap
    (function() {
      var link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css';

      // Check if Bootstrap CSS loaded successfully
      setTimeout(function() {
        var bootstrapLoaded = document.querySelector('link[href*="bootstrap"]');
        if (!bootstrapLoaded || !bootstrapLoaded.sheet) {
          document.head.appendChild(link);
          console.log('Bootstrap CSS fallback loaded');
        }
      }, 1000);
    })();

    // CSS Loading Check
    (function() {
      setTimeout(function() {
        var styles = getComputedStyle(document.body);
        if (styles.getPropertyValue('--bg') === '') {
          console.warn('CSS variables not loaded, theme may not be working');
        } else {
          console.log('CSS loaded successfully');
        }
      }, 500);
    })();
  </script>

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

    /* Full Dark Background for Dark Mode */
    html[data-theme="dark"] {
      background: #0F1115;
    }

    html[data-theme="dark"] body {
      background: #0F1115;
      color: #ECEEF2;
    }

    html[data-theme="dark"] .container {
      background: transparent;
    }

    /* ENHANCED NAVBAR (glassy) */
    .vel-nav{
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      background: var(--glass-strong) !important;
      border-bottom: 1px solid var(--ring);
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    /* Brand Styling */
    .navbar-brand {
      color: var(--text) !important;
      font-size: 1.4rem;
      transition: all 0.3s ease;
    }

    .navbar-brand:hover {
      color: var(--gold) !important;
      transform: scale(1.05);
    }

    .brand-icon {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--gold), #ffd700);
      border-radius: 8px;
      color: #111216 !important;
    }

    .brand-text {
      font-weight: 700;
      font-family: 'Cinzel', serif;
      letter-spacing: .08em;
      text-transform: uppercase;
      background: linear-gradient(135deg, var(--text), var(--gold));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* AVENUE Inline Logo */
    .logo-avenue{ height:32px; width:auto; display:block; }
    .logo-avenue .mark-bg{ fill:url(#avenueGrad); }
    .logo-avenue .mark-text{ font-family:'Cinzel', serif; font-weight:700; fill:#111216; }
    .logo-avenue .word-text{ font-family:'Cinzel', serif; font-weight:700; letter-spacing:.12em; fill:url(#avenueGradText); }

    /* Navigation Links */
    .nav-link {
      color: var(--text) !important;
      font-weight: 500;
      padding: 0.75rem 1rem !important;
      border-radius: 8px;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-link:hover {
      color: var(--gold) !important;
      background: rgba(240, 194, 75, 0.1);
      transform: translateY(-2px);
    }

    .nav-link i {
      font-size: 0.9rem;
      opacity: 0.8;
    }

    .nav-link:hover i {
      opacity: 1;
      transform: scale(1.1);
    }

    /* Theme Switcher */
    .btn-theme-switcher {
      background: var(--glass);
      border: 1px solid var(--border);
      color: var(--text);
      border-radius: 8px;
      padding: 0.5rem 0.75rem;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .btn-theme-switcher:hover {
      background: var(--gold);
      color: #111216;
      border-color: var(--gold);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(240, 194, 75, 0.3);
    }

    .btn-theme-switcher i {
      transition: transform 0.3s ease;
    }

    .btn-theme-switcher:hover i {
      transform: rotate(180deg);
    }

    /* Language Switcher */
    .btn-language-switcher {
      background: var(--glass);
      border: 1px solid var(--border);
      color: var(--text);
      border-radius: 8px;
      padding: 0.5rem 0.75rem;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .btn-language-switcher:hover {
      background: var(--gold);
      color: #111216;
      border-color: var(--gold);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(240, 194, 75, 0.3);
    }

    .btn-language-switcher i {
      transition: transform 0.3s ease;
    }

    .btn-language-switcher:hover i {
      transform: scale(1.1);
    }

    /* Dropdown Menu */
    .dropdown-menu {
      background: var(--glass);
      border: 1px solid var(--border);
      border-radius: 12px;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      padding: 0.5rem;
    }

    .dropdown-item {
      color: var(--text);
      border-radius: 8px;
      padding: 0.5rem 0.75rem;
      transition: all 0.3s ease;
    }

    .dropdown-item:hover {
      background: rgba(240, 194, 75, 0.1);
      color: var(--gold);
      transform: translateX(4px);
    }

    .dropdown-item i {
      width: 16px;
      text-align: center;
    }

    /* Mobile Toggle */
    .navbar-toggler {
      color: var(--text);
      border-color: var(--border);
      border-radius: 8px;
      padding: 0.5rem;
    }

    .navbar-toggler:focus {
      box-shadow: 0 0 0 3px rgba(240, 194, 75, 0.25);
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(17,18,22,.85)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* RTL Support */
    [dir="rtl"] .navbar-brand {
      text-align: right;
    }

    [dir="rtl"] .nav-link {
      text-align: right;
    }

    [dir="rtl"] .dropdown-menu {
      text-align: right;
    }

    [dir="rtl"] .dropdown-item:hover {
      transform: translateX(-4px);
    }

    /* Responsive Design */
    @media (max-width: 991.98px) {
      .navbar-nav {
        text-align: center;
        margin-top: 1rem;
      }

      .nav-link {
        padding: 0.75rem 1rem !important;
        margin: 0.25rem 0;
      }

      .d-flex.align-items-center.gap-2 {
        justify-content: center;
        margin-top: 1rem;
      }
    }

    /* Dark Mode Enhancements */
    html[data-theme="dark"] .vel-nav {
      background: rgba(17, 18, 22, 0.95) !important;
      border-bottom-color: rgba(255,255,255,0.1);
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    html[data-theme="dark"] .navbar-brand {
      color: #ffffff !important;
    }

    html[data-theme="dark"] .brand-text {
      background: linear-gradient(135deg, #ffffff, var(--gold));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    html[data-theme="dark"] .nav-link {
      color: rgba(255,255,255,0.8) !important;
    }

    html[data-theme="dark"] .nav-link:hover {
      color: var(--gold) !important;
      background: rgba(240, 194, 75, 0.15);
    }

    html[data-theme="dark"] .btn-theme-switcher,
    html[data-theme="dark"] .btn-language-switcher {
      background: rgba(26,26,26,0.8);
      border-color: rgba(255,255,255,0.1);
      color: rgba(255,255,255,0.8);
    }

    html[data-theme="dark"] .btn-theme-switcher:hover,
    html[data-theme="dark"] .btn-language-switcher:hover {
      background: var(--gold);
      color: #111216;
      border-color: var(--gold);
    }

    html[data-theme="dark"] .dropdown-menu {
      background: rgba(26,26,26,0.95);
      border-color: rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .dropdown-item {
      color: rgba(255,255,255,0.8);
    }

    html[data-theme="dark"] .dropdown-item:hover {
      background: rgba(240, 194, 75, 0.15);
      color: var(--gold);
    }

    html[data-theme="dark"] .navbar-toggler {
      border-color: rgba(255,255,255,0.2);
    }

    html[data-theme="dark"] .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(236,238,242,.9)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

        /* Ultra Shiny Gold Buttons */
    .btn-vel-gold{
      background: linear-gradient(135deg, #FFF8DC, #FFD700, #FFA500, #FFD700, #FFF8DC);
      background-size: 300% 300%;
      animation: ultra-shiny-gold 4s ease-in-out infinite;
      color: #111216;
      border: none;
      font-weight: 600;
      box-shadow:
        0 10px 22px rgba(255,215,0,0.4),
        0 0 30px rgba(255,215,0,0.3),
        0 0 50px rgba(255,215,0,0.2),
        inset 0 1px 0 rgba(255,255,255,0.6),
        inset 0 -1px 0 rgba(0,0,0,0.1);
      transition: all var(--speed) ease;
      position: relative;
      overflow: hidden;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .btn-vel-gold::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg,
        transparent,
        rgba(255,255,255,0.1),
        rgba(255,255,255,0.4),
        rgba(255,255,255,0.1),
        transparent);
      transform: rotate(45deg);
      animation: ultra-shiny-sweep 2.5s ease-in-out infinite;
      filter: blur(1px);
    }

        .btn-vel-gold:hover{
      transform: translateY(-3px) scale(1.02);
      filter: brightness(1.2) saturate(1.3) contrast(1.1);
      box-shadow:
        0 20px 40px rgba(255,215,0,0.5),
        0 0 50px rgba(255,215,0,0.4),
        0 0 80px rgba(255,215,0,0.3),
        inset 0 1px 0 rgba(255,255,255,0.8),
        inset 0 -1px 0 rgba(0,0,0,0.2);
      animation: ultra-shiny-gold 1.5s ease-in-out infinite;
    }

    .btn-vel-outline{
      color: var(--text);
      border: 2px solid;
      border-image: linear-gradient(45deg, #FFF8DC, #FFD700, #FFA500, #FFD700, #FFF8DC) 1;
      background: linear-gradient(90deg, rgba(255,215,0,0.08), transparent);
      transition: all var(--speed) ease;
      position: relative;
      overflow: hidden;
      box-shadow:
        0 0 20px rgba(255,215,0,0.2),
        inset 0 0 20px rgba(255,215,0,0.05);
    }

    .btn-vel-outline::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg,
        transparent,
        rgba(255,215,0,0.1),
        rgba(255,215,0,0.3),
        rgba(255,215,0,0.1),
        transparent);
      transition: left 0.6s ease;
      filter: blur(0.5px);
    }

    .btn-vel-outline:hover{
      background: linear-gradient(90deg, rgba(255,215,0,0.15), transparent 70%);
      border-image: linear-gradient(45deg, #FFA500, #FFD700, #FFF8DC, #FFD700, #FFA500) 1;
      transform: translateY(-3px) scale(1.02);
      box-shadow:
        0 12px 30px rgba(255,215,0,0.4),
        0 0 40px rgba(255,215,0,0.2),
        inset 0 0 30px rgba(255,215,0,0.1);
      filter: brightness(1.1) saturate(1.2);
    }

    .btn-vel-outline:hover::before {
      left: 100%;
    }

    /* Professional UI/UX Animations - Eye-Friendly */
    @keyframes subtle-float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-2px); }
    }

    @keyframes gentle-pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.8; }
    }

    /* Glass & panels */
    .panel{ background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); }
    .glass{ background:var(--glass)!important; border:1px solid var(--border); border-radius:var(--radius); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); box-shadow:var(--shadow); }

    /* Professional Gold Elements - Eye-Friendly Design */
    .brand-name {
      color: #d69e2e;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .brand-name:hover {
      color: #b7791f;
      transform: translateX(2px);
    }

    .footer-head::after {
      background: linear-gradient(90deg, #f0c24b, transparent);
      transition: width 0.3s ease;
    }

    .footer-head:hover::after {
      width: 50px;
    }

    .social-link:hover {
      background: #f0c24b;
      color: #1a202c;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(240,194,75,0.3);
    }

    .value-svg, .no-brands-svg, .browse-icon {
      color: #f0c24b;
      transition: all 0.3s ease;
    }

    .value-svg:hover, .no-brands-svg:hover, .browse-icon:hover {
      color: #d69e2e;
      transform: scale(1.05);
    }

    .brand-name:hover, .value-svg:hover, .no-brands-svg:hover, .browse-icon:hover {
      filter: drop-shadow(0 4px 8px rgba(255,215,0,0.6));
      transform: scale(1.05);
      transition: all 0.3s ease;
    }

    /* Professional Gold Links - Eye-Friendly */
    a:not(.btn):not(.nav-link):not(.footer-link) {
      color: #f0c24b;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    a:not(.btn):not(.nav-link):not(.footer-link):hover {
      color: #d69e2e;
      text-decoration: underline;
      transition: all 0.3s ease;
    }

    /* Footer */
    .vel-footer{
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      background:
        radial-gradient(800px 400px at 95% 0%, rgba(240,194,75,.06), transparent 60%),
        var(--surface);
      border-top:1px solid var(--border);
    }
    .footer-head{ font-weight:700; margin-bottom:.5rem; }
    .footer-link{ color:var(--text)!important; text-decoration:none; }
    .footer-link:hover{ color:#FFC94D!important; }

    /* Enhanced Footer Styling - Best Footer in the World */
    .vel-footer {
      background:
        radial-gradient(800px 400px at 95% 0%, rgba(240,194,75,0.08), transparent 60%),
        var(--surface);
      border-top: 1px solid var(--border);
      position: relative;
      overflow: hidden;
    }

    .vel-footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg,
        transparent 0%,
        rgba(240,194,75,0.3) 50%,
        transparent 100%);
    }

    .footer-head {
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--text);
      font-size: 1.1rem;
      position: relative;
    }

        .footer-head::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 30px;
      height: 2px;
      background: linear-gradient(90deg, #f0c24b, transparent);
      border-radius: 1px;
      transition: width 0.3s ease;
    }

    .footer-head:hover::after {
      width: 50px;
    }

    .footer-description {
      color: var(--muted);
      line-height: 1.6;
      margin-bottom: 1.5rem;
      max-width: 40ch;
    }

    .footer-links {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer-links li {
      margin-bottom: 0.75rem;
    }

    .footer-link {
      color: var(--muted);
      text-decoration: none;
      transition: all 0.3s ease;
      position: relative;
      padding-left: 0;
    }

    .footer-link::before {
      content: '→';
      position: absolute;
      left: -15px;
      opacity: 0;
      transition: all 0.3s ease;
      color: var(--gold);
    }

    .footer-link:hover {
      color: #f0c24b;
      padding-left: 15px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .footer-link:hover::before {
      opacity: 1;
    }

    .footer-social {
      margin-top: 1.5rem;
    }

    .social-links {
      display: flex;
      gap: 0.75rem;
    }

    .social-link {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--glass);
      border: 1px solid var(--border);
      color: var(--muted);
      text-decoration: none;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

        .social-link:hover {
      background: #f0c24b;
      color: #111216;
      border-color: #f0c24b;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(240,194,75,0.3);
      transition: all 0.3s ease;
    }

    .social-icon {
      font-size: 1.2rem;
    }

    /* Animated SVG Icons */
    .social-icon svg,
    .heart-icon,
    .inline-icon {
      transition: all 0.3s ease;
      animation: icon-float 3s ease-in-out infinite;
    }

    .social-icon svg:hover,
    .heart-icon:hover,
    .inline-icon:hover {
      transform: scale(1.2) rotate(5deg);
      filter: drop-shadow(0 4px 8px rgba(240,194,75,0.4));
    }

    .heart-icon {
      display: inline-block;
      vertical-align: middle;
      margin: 0 4px;
      color: #ff6b6b;
      animation: heart-beat 2s ease-in-out infinite;
    }

    .heart-icon:hover {
      animation: heart-beat 0.5s ease-in-out infinite;
      color: #ff4757;
    }

    .inline-icon {
      display: inline-block;
      vertical-align: middle;
      margin: 0 4px;
      color: var(--gold);
    }

    @keyframes icon-float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-3px); }
    }

    @keyframes heart-beat {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    /* Social Media Icon Specific Styles */
    .social-link:hover .social-icon svg {
      transform: scale(1.1) rotate(5deg);
      filter: drop-shadow(0 4px 12px rgba(240,194,75,0.6));
    }

    /* Original Social Media Brand Colors */
    .facebook-link .social-icon {
      color: #1877f2;
    }

    .facebook-link:hover .social-icon {
      color: #166fe5;
      filter: drop-shadow(0 4px 12px rgba(24, 119, 242, 0.4));
    }

    .instagram-link .social-icon {
      background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .instagram-link:hover .social-icon {
      filter: drop-shadow(0 4px 12px rgba(220, 39, 67, 0.4));
    }

    .linkedin-link .social-icon {
      color: #0077b5;
    }

    .linkedin-link:hover .social-icon {
      color: #006097;
      filter: drop-shadow(0 4px 12px rgba(0, 119, 181, 0.4));
    }

    /* Additional SVG Icon Styles */
    .value-svg,
    .no-brands-svg,
    .browse-icon {
      transition: all 0.3s ease;
      animation: icon-float 3s ease-in-out infinite;
      color: var(--gold);
    }

    .value-svg:hover,
    .no-brands-svg:hover,
    .browse-icon:hover {
      transform: scale(1.1) rotate(5deg);
      filter: drop-shadow(0 4px 8px rgba(240,194,75,0.4));
    }

    .newsletter-form .form-control {
      background: var(--glass);
      border: 1px solid var(--border);
      color: var(--text);
      transition: all 0.3s ease;
    }

    .newsletter-form .form-control:focus {
      background: var(--glass-strong);
      border-color: var(--gold);
      box-shadow: 0 0 0 0.2rem rgba(240,194,75,0.25);
      color: var(--text);
    }

    .newsletter-form .form-control::placeholder {
      color: var(--muted);
    }

    .newsletter-benefits {
      font-size: 0.85rem;
      line-height: 1.4;
    }

    .footer-divider {
      border-color: var(--border);
      opacity: 0.3;
      margin: 2rem 0;
    }

    .footer-bottom {
      padding-top: 1rem;
    }

    .copyright-text {
      color: var(--muted);
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .developer-credit {
      display: block;
      margin-top: 0.5rem;
      color: var(--text);
      font-size: 0.85rem;
    }

    .developer-credit strong {
      color: #f0c24b;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .developer-credit strong:hover {
      color: #d69e2e;
      transform: translateX(2px);
    }

    .footer-credits {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      align-items: flex-end;
    }

    .crafted-with, .powered-by {
      color: var(--muted);
      font-size: 0.85rem;
    }

    .luxe-gradient {
      background: var(--accent-grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 600;
    }

    .tech-stack {
      color: #f0c24b;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .tech-stack:hover {
      color: #d69e2e;
      transform: translateX(2px);
    }

    /* Dark Mode Footer Enhancements */
    html[data-theme="dark"] .vel-footer {
      background:
        radial-gradient(800px 400px at 95% 0%, rgba(240,194,75,0.12), transparent 60%),
        rgba(17, 18, 22, 0.98);
      border-top-color: rgba(255, 255, 255, 0.1);
    }

    html[data-theme="dark"] .footer-head {
      color: #ffffff;
    }

    html[data-theme="dark"] .footer-description {
      color: rgba(255, 255, 255, 0.7);
    }

    html[data-theme="dark"] .footer-link {
      color: rgba(255, 255, 255, 0.7);
    }

    html[data-theme="dark"] .footer-link:hover {
      color: var(--gold);
    }

    html[data-theme="dark"] .social-link {
      background: rgba(26, 26, 26, 0.8);
      border-color: rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.7);
    }

    html[data-theme="dark"] .social-link:hover {
      background: var(--gold);
      color: #111216;
      border-color: var(--gold);
    }

    html[data-theme="dark"] .newsletter-form .form-control {
      background: rgba(26, 26, 26, 0.8);
      border-color: rgba(255, 255, 255, 0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .newsletter-form .form-control:focus {
      background: rgba(26, 26, 26, 0.9);
      border-color: var(--gold);
      color: #ffffff;
    }

    html[data-theme="dark"] .newsletter-form .form-control::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    html[data-theme="dark"] .footer-divider {
      border-color: rgba(255, 255, 255, 0.1);
    }

    html[data-theme="dark"] .copyright-text {
      color: rgba(255, 255, 255, 0.7);
    }

    html[data-theme="dark"] .developer-credit {
      color: rgba(255, 255, 255, 0.9);
    }

    html[data-theme="dark"] .crafted-with,
    html[data-theme="dark"] .powered-by {
      color: rgba(255, 255, 255, 0.7);
    }

    /* Light Mode Footer Enhancements */
    html[data-theme="light"] .vel-footer {
      background:
        radial-gradient(800px 400px at 95% 0%, rgba(240,194,75,0.06), transparent 60%),
        #f8f9fa;
      border-top-color: rgba(0, 0, 0, 0.1);
    }

    html[data-theme="light"] .footer-head {
      color: #111216;
    }

    html[data-theme="light"] .footer-description {
      color: #6c757d;
    }

    html[data-theme="light"] .footer-link {
      color: #6c757d;
    }

    html[data-theme="light"] .footer-link:hover {
      color: var(--gold);
    }

    html[data-theme="light"] .social-link {
      background: rgba(255, 255, 255, 0.8);
      border-color: rgba(0, 0, 0, 0.1);
      color: #6c757d;
    }

    html[data-theme="light"] .social-link:hover {
      background: var(--gold);
      color: #111216;
      border-color: var(--gold);
    }

    html[data-theme="light"] .newsletter-form .form-control {
      background: rgba(255, 255, 255, 0.8);
      border-color: rgba(0, 0, 0, 0.1);
      color: #111216;
    }

    html[data-theme="light"] .newsletter-form .form-control:focus {
      background: rgba(255, 255, 255, 0.9);
      border-color: var(--gold);
      color: #111216;
    }

    html[data-theme="light"] .newsletter-form .form-control::placeholder {
      color: #6c757d;
    }

    html[data-theme="light"] .footer-divider {
      border-color: rgba(0, 0, 0, 0.1);
    }

    html[data-theme="light"] .copyright-text {
      color: #6c757d;
    }

    html[data-theme="light"] .developer-credit {
      color: #111216;
    }

    html[data-theme="light"] .crafted-with,
    html[data-theme="light"] .powered-by {
      color: #6c757d;
    }

    /* Responsive Footer */
    @media (max-width: 768px) {
      .footer-bottom {
        text-align: center;
      }

      .footer-credits {
        align-items: center;
        margin-top: 1rem;
      }

      .social-links {
        justify-content: center;
      }

      .footer-head::after {
        left: 50%;
        transform: translateX(-50%);
      }
    }

    /* WhatsApp Floating Button - Enhanced for Perfect Theme Support */
    .wa-fab {
      position: fixed;
      right: 20px;
      bottom: 20px;
      z-index: 1000;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1rem;
      border-radius: 50px;
      text-decoration: none;
      color: #ffffff;
      background: linear-gradient(135deg, #25D366, #128C7E);
      border: 1px solid rgba(37, 211, 102, 0.3);
      box-shadow:
        0 8px 24px rgba(37, 211, 102, 0.3),
        0 0 0 1px rgba(37, 211, 102, 0.1);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      font-weight: 600;
      font-size: 0.9rem;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      overflow: hidden;
    }

    .wa-fab::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg,
        rgba(255, 255, 255, 0.1) 0%,
        rgba(255, 255, 255, 0.05) 50%,
        transparent 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .wa-fab:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow:
        0 12px 32px rgba(37, 211, 102, 0.4),
        0 0 0 1px rgba(37, 211, 102, 0.2);
      color: #ffffff;
      text-decoration: none;
      border-color: rgba(37, 211, 102, 0.5);
    }

    .wa-fab:hover::before {
      opacity: 1;
    }

    .wa-fab:active {
      transform: translateY(-1px) scale(1.02);
      box-shadow:
        0 6px 20px rgba(37, 211, 102, 0.3),
        0 0 0 1px rgba(37, 211, 102, 0.2);
    }

    .wa-fab span {
      position: relative;
      z-index: 2;
    }

    .wa-icon {
      width: 20px;
      height: 20px;
      background: #ffffff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      position: relative;
      z-index: 2;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .wa-icon::before {
      content: '';
      width: 12px;
      height: 12px;
      background: #25D366;
      border-radius: 50%;
      mask: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.86 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" fill="%2325D366"/></svg>') center/contain no-repeat;
      -webkit-mask: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.86 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" fill="%2325D366"/></svg>') center/contain no-repeat;
    }

    /* Light Theme WhatsApp Button */
    html[data-theme="light"] .wa-fab {
      background: linear-gradient(135deg, #25D366, #128C7E);
      border-color: rgba(37, 211, 102, 0.3);
      box-shadow:
        0 8px 24px rgba(37, 211, 102, 0.25),
        0 0 0 1px rgba(37, 211, 102, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    html[data-theme="light"] .wa-fab:hover {
      box-shadow:
        0 12px 32px rgba(37, 211, 102, 0.35),
        0 0 0 1px rgba(37, 211, 102, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
      border-color: rgba(37, 211, 102, 0.4);
    }

    html[data-theme="light"] .wa-fab:active {
      box-shadow:
        0 6px 20px rgba(37, 211, 102, 0.25),
        0 0 0 1px rgba(37, 211, 102, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    /* Dark Theme WhatsApp Button */
    html[data-theme="dark"] .wa-fab {
      background: linear-gradient(135deg, #25D366, #128C7E);
      border-color: rgba(37, 211, 102, 0.4);
      box-shadow:
        0 8px 24px rgba(37, 211, 102, 0.4),
        0 0 0 1px rgba(37, 211, 102, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    html[data-theme="dark"] .wa-fab:hover {
      box-shadow:
        0 12px 32px rgba(37, 211, 102, 0.5),
        0 0 0 1px rgba(37, 211, 102, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.15);
      border-color: rgba(37, 211, 102, 0.5);
    }

    html[data-theme="dark"] .wa-fab:active {
      box-shadow:
        0 6px 20px rgba(37, 211, 102, 0.4),
        0 0 0 1px rgba(37, 211, 102, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    /* Enhanced icon styling for both themes */
    html[data-theme="light"] .wa-icon {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    html[data-theme="dark"] .wa-icon {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    /* Responsive WhatsApp button */
    @media (max-width: 768px) {
      .wa-fab {
        right: 15px;
        bottom: 15px;
        padding: 0.6rem 0.8rem;
        font-size: 0.8rem;
      }

      .wa-icon {
        width: 18px;
        height: 18px;
      }

      .wa-icon::before {
        width: 10px;
        height: 10px;
      }
    }

    @media (max-width: 576px) {
      .wa-fab {
        right: 10px;
        bottom: 10px;
        padding: 0.5rem 0.7rem;
        font-size: 0.75rem;
      }
    }

    /* Animation for initial load */
    .wa-fab {
      animation: wa-fab-enter 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes wa-fab-enter {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.8);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* Pulse animation for attention */
    .wa-fab::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 100%;
      height: 100%;
      background: rgba(37, 211, 102, 0.3);
      border-radius: 50px;
      transform: translate(-50%, -50%) scale(0);
      animation: wa-pulse 3s infinite;
      pointer-events: none;
    }

    @keyframes wa-pulse {
      0% {
        transform: translate(-50%, -50%) scale(0);
        opacity: 1;
      }
      100% {
        transform: translate(-50%, -50%) scale(1.5);
        opacity: 0;
      }
    }

    /* Animations & cards */
    .reveal{ opacity:0; transform:translateY(14px); transition:opacity 600ms ease, transform 600ms ease; }
    .reveal.visible{ opacity:1; transform:translateY(0); }
    .luxe-card{ background:var(--glass); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); transition:transform .28s ease, box-shadow .28s ease; will-change:transform; }
    .luxe-card:hover{ transform:translateY(-6px); box-shadow:0 24px 60px rgba(0,0,0,.16); }
    .luxe-thumb{ aspect-ratio:1/1; border-radius: calc(var(--radius) - 6px); overflow:hidden; background:var(--surface); }
    .luxe-thumb img{ width:100%; height:100%; object-fit:cover; transform:scale(1.01); transition:transform .28s ease, filter .28s ease; }
    .luxe-card:hover .luxe-thumb img{ transform:scale(1.04); filter:saturate(1.04) contrast(1.02); }

    /* Enhanced Product Card Details */
    .product-card .card-body {
      padding: 1.25rem;
      display: flex;
      flex-direction: column;
      height: 100%;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .product-card .product-name {
      font-family: 'Georgia', serif;
      font-weight: 700;
      font-size: 1.1rem;
      line-height: 1.3;
      color: var(--text);
      margin-bottom: 0.5rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      min-height: 2.6rem;
    }

    .product-card .brand-name {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--gold);
      margin-bottom: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      opacity: 0.8;
    }

    .product-card .price-section {
      margin-top: auto;
      padding-top: 0.75rem;
      border-top: 1px solid rgba(240,194,75,0.2);
    }

    .product-card .price {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 0.25rem;
    }

    .product-card .original-price {
      font-size: 0.875rem;
      color: var(--muted);
      text-decoration: line-through;
      margin-right: 0.5rem;
      opacity: 0.7;
    }

    .product-card .sale-price {
      font-size: 1.25rem;
      font-weight: 700;
      color: #10b981;
      text-shadow: 0 1px 2px rgba(16, 185, 129, 0.2);
    }

    .product-card .price-currency {
      font-size: 0.875rem;
      color: var(--muted);
      margin-left: 0.25rem;
      opacity: 0.8;
    }

    /* Enhanced Product Card Animations */
    .product-card {
      animation: card-enter 0.6s ease-out;
    }

    @keyframes card-enter {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* Product Card Hover Effects */
    .product-card:hover .product-name {
      color: var(--gold);
      transition: color 0.3s ease;
    }

    .product-card:hover .brand-name {
      opacity: 1;
      transform: translateX(5px);
      transition: all 0.3s ease;
    }

    .product-card:hover .price-section {
      border-top-color: rgba(240,194,75,0.4);
      transition: border-color 0.3s ease;
    }

    /* Responsive Product Card Improvements */
    @media (max-width: 768px) {
      .product-card .card-body {
        padding: 1rem;
      }

      .product-card .product-name {
        font-size: 1rem;
        min-height: 2.4rem;
      }

      .product-card .price {
        font-size: 1.1rem;
      }

      .sale-badge {
        padding: 4px 8px;
        font-size: 0.7rem;
      }
    }

    /* Dark Mode Enhancements for All Elements */
    html[data-theme="dark"] .product-card {
      background: linear-gradient(135deg,
        rgba(240,194,75,0.08) 0%,
        rgba(120,119,198,0.08) 100%);
      border: 1px solid rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .product-card .card-body {
      background: rgba(0,0,0,0.3);
    }

    html[data-theme="dark"] .crystal-card {
      background: linear-gradient(135deg,
        rgba(255,255,255,0.05) 0%,
        rgba(255,255,255,0.02) 50%,
        rgba(255,255,255,0.01) 100%);
      border-color: rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .section-title {
      color: #ffffff;
    }

    html[data-theme="dark"] .lead {
      color: rgba(255,255,255,0.8);
    }

    html[data-theme="dark"] .hero-section {
      background: linear-gradient(135deg,
        rgba(240,194,75,0.1) 0%,
        rgba(17,18,22,0.95) 100%);
    }

    html[data-theme="dark"] .cta-section {
      background: linear-gradient(135deg,
        rgba(240,194,75,0.15) 0%,
        rgba(17,18,22,0.98) 100%);
    }

    html[data-theme="dark"] .sale-badge {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #ffffff;
    }

    html[data-theme="dark"] .btn-vel-outline {
      color: #ffffff;
      border-color: var(--gold);
    }

    html[data-theme="dark"] .btn-vel-outline:hover {
      background: linear-gradient(90deg, rgba(240,194,75,0.2), transparent);
      color: var(--gold);
    }

    /* Floating Elements */
    .floating-element {
      position: absolute;
      z-index: 1;
      opacity: 0.6;
      animation: float 6s ease-in-out infinite;
      pointer-events: none;
    }

    .floating-icon {
      color: var(--gold);
      filter: drop-shadow(0 2px 4px rgba(240,194,75,0.3));
      animation: icon-float 4s ease-in-out infinite;
    }

    .floating-icon:hover {
      transform: scale(1.1) rotate(10deg);
      filter: drop-shadow(0 4px 8px rgba(240,194,75,0.6));
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(5deg); }
    }

    /* Additional Dark Mode Styles for Complete Coverage */
    html[data-theme="dark"] .container {
      color: var(--text);
    }

    html[data-theme="dark"] .section-title {
      color: #ffffff;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    html[data-theme="dark"] .lead {
      color: rgba(255,255,255,0.9);
    }

    html[data-theme="dark"] .text-muted {
      color: rgba(255,255,255,0.6) !important;
    }

    html[data-theme="dark"] .card {
      background: rgba(0,0,0,0.4);
      border-color: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .card-header {
      background: rgba(0,0,0,0.5);
      border-bottom-color: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .card-footer {
      background: rgba(0,0,0,0.5);
      border-top-color: rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .form-control {
      background: rgba(0,0,0,0.6);
      border-color: rgba(255,255,255,0.2);
      color: #ffffff;
    }

    html[data-theme="dark"] .form-control:focus {
      background: rgba(0,0,0,0.8);
      border-color: var(--gold);
      color: #ffffff;
      box-shadow: 0 0 0 0.2rem rgba(240,194,75,0.25);
    }

    html[data-theme="dark"] .form-control::placeholder {
      color: rgba(255,255,255,0.5);
    }

    html[data-theme="dark"] .btn-outline-secondary {
      color: rgba(255,255,255,0.8);
      border-color: rgba(255,255,255,0.3);
    }

    html[data-theme="dark"] .btn-outline-secondary:hover {
      background: rgba(255,255,255,0.1);
      color: #ffffff;
      border-color: rgba(255,255,255,0.5);
    }

    html[data-theme="dark"] .table {
      color: #ffffff;
    }

    html[data-theme="dark"] .table th {
      background: rgba(0,0,0,0.6);
      border-color: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .table td {
      border-color: rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > td {
      background: rgba(0,0,0,0.3);
    }

    html[data-theme="dark"] .table-hover > tbody > tr:hover > td {
      background: rgba(0,0,0,0.5);
    }

    html[data-theme="dark"] .alert {
      background: rgba(0,0,0,0.6);
      border-color: rgba(255,255,255,0.2);
      color: #ffffff;
    }

    html[data-theme="dark"] .alert-success {
      background: rgba(16,185,129,0.2);
      border-color: rgba(16,185,129,0.4);
      color: #10b981;
    }

    html[data-theme="dark"] .alert-warning {
      background: rgba(245,158,11,0.2);
      border-color: rgba(245,158,11,0.4);
      color: #f59e0b;
    }

    html[data-theme="dark"] .alert-danger {
      background: rgba(239,68,68,0.2);
      border-color: rgba(239,68,68,0.4);
      color: #ef4444;
    }

    html[data-theme="dark"] .alert-info {
      background: rgba(59,130,246,0.2);
      border-color: rgba(59,130,246,0.4);
      color: #3b82f6;
    }

    html[data-theme="dark"] .badge {
      background: rgba(0,0,0,0.6);
      color: #ffffff;
    }

    html[data-theme="dark"] .badge.bg-primary {
      background: rgba(59,130,246,0.8) !important;
    }

    html[data-theme="dark"] .badge.bg-success {
      background: rgba(16,185,129,0.8) !important;
    }

    html[data-theme="dark"] .badge.bg-warning {
      background: rgba(245,158,11,0.8) !important;
    }

    html[data-theme="dark"] .badge.bg-danger {
      background: rgba(239,68,68,0.8) !important;
    }

    html[data-theme="dark"] .badge.bg-info {
      background: rgba(59,130,246,0.8) !important;
    }

    html[data-theme="dark"] .modal-content {
      background: rgba(22,26,32,0.98);
      border-color: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .modal-header {
      border-bottom-color: rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .modal-footer {
      border-top-color: rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .dropdown-menu {
      background: rgba(22,26,32,0.98);
      border-color: rgba(255,255,255,0.1);
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }

    html[data-theme="dark"] .dropdown-item {
      color: rgba(255,255,255,0.8);
    }

    html[data-theme="dark"] .dropdown-item:hover {
      background: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .dropdown-divider {
      border-color: rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .list-group-item {
      background: rgba(0,0,0,0.4);
      border-color: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .list-group-item:hover {
      background: rgba(0,0,0,0.6);
    }

    html[data-theme="dark"] .pagination .page-link {
      background: rgba(0,0,0,0.6);
      border-color: rgba(255,255,255,0.1);
      color: rgba(255,255,255,0.8);
    }

    html[data-theme="dark"] .pagination .page-link:hover {
      background: rgba(0,0,0,0.8);
      color: #ffffff;
    }

    html[data-theme="dark"] .pagination .page-item.active .page-link {
      background: var(--gold);
      border-color: var(--gold);
      color: #111216;
    }

    html[data-theme="dark"] .breadcrumb {
      background: rgba(0,0,0,0.4);
    }

    html[data-theme="dark"] .breadcrumb-item + .breadcrumb-item::before {
      color: rgba(255,255,255,0.5);
    }

    html[data-theme="dark"] .nav-tabs {
      border-bottom-color: rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] .nav-tabs .nav-link {
      color: rgba(255,255,255,0.7);
      border-color: transparent;
    }

    html[data-theme="dark"] .nav-tabs .nav-link:hover {
      color: #ffffff;
      border-color: rgba(255,255,255,0.2);
    }

    html[data-theme="dark"] .nav-tabs .nav-link.active {
      background: rgba(0,0,0,0.6);
      border-color: rgba(255,255,255,0.2);
      color: #ffffff;
    }

    html[data-theme="dark"] .nav-pills .nav-link {
      color: rgba(255,255,255,0.7);
    }

    html[data-theme="dark"] .nav-pills .nav-link.active {
      background: var(--gold);
      color: #111216;
    }

    html[data-theme="dark"] .progress {
      background: rgba(0,0,0,0.4);
    }

    html[data-theme="dark"] .progress-bar {
      background: var(--gold);
    }

    html[data-theme="dark"] .tooltip {
      background: rgba(22,26,32,0.98);
      color: #ffffff;
    }

    html[data-theme="dark"] .tooltip .tooltip-arrow::before {
      border-top-color: rgba(22,26,32,0.98);
    }

    html[data-theme="dark"] .popover {
      background: rgba(22,26,32,0.98);
      border-color: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .popover-header {
      background: rgba(0,0,0,0.6);
      border-bottom-color: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .popover-arrow::before {
      border-color: rgba(22,26,32,0.98);
    }

    /* Additional Dark Mode Styles for Navigation and UI Elements */
    html[data-theme="dark"] .navbar-nav .nav-link {
      color: rgba(255,255,255,0.8) !important;
    }

    html[data-theme="dark"] .navbar-nav .nav-link:hover {
      color: var(--gold) !important;
    }

    html[data-theme="dark"] .navbar-brand {
      color: #ffffff !important;
    }

    html[data-theme="dark"] .navbar .form-control {
      background: rgba(0,0,0,0.6);
      border-color: rgba(255,255,255,0.2);
      color: #ffffff;
    }

    html[data-theme="dark"] .navbar .form-control:focus {
      background: rgba(0,0,0,0.8);
      border-color: var(--gold);
      color: #ffffff;
      box-shadow: 0 0 0 0.2rem rgba(240,194,75,0.25);
    }

    html[data-theme="dark"] .navbar .form-control::placeholder {
      color: rgba(255,255,255,0.5);
    }

    html[data-theme="dark"] .btn-vel-outline {
      color: rgba(255,255,255,0.8);
      border-color: var(--gold);
    }

    html[data-theme="dark"] .btn-vel-outline:hover {
      background: linear-gradient(90deg, rgba(240,194,75,0.2), transparent);
      color: var(--gold);
    }

    /* Dark Mode for All Text Elements */
    html[data-theme="dark"] h1,
    html[data-theme="dark"] h2,
    html[data-theme="dark"] h3,
    html[data-theme="dark"] h4,
    html[data-theme="dark"] h5,
    html[data-theme="dark"] h6 {
      color: #ffffff;
    }

    html[data-theme="dark"] p {
      color: rgba(255,255,255,0.9);
    }

    html[data-theme="dark"] small {
      color: rgba(255,255,255,0.7);
    }

    /* Dark Mode for Links */
    html[data-theme="dark"] a:not(.btn):not(.nav-link):not(.footer-link) {
      color: var(--gold);
    }

    html[data-theme="dark"] a:not(.btn):not(.nav-link):not(.footer-link):hover {
      color: #ffffff;
    }

    /* Dark Mode for Code and Pre Elements */
    html[data-theme="dark"] code {
      background: rgba(0,0,0,0.6);
      color: #10b981;
      border: 1px solid rgba(255,255,255,0.1);
    }

    html[data-theme="dark"] pre {
      background: rgba(0,0,0,0.6);
      color: #ffffff;
      border: 1px solid rgba(255,255,255,0.1);
    }

    /* Dark Mode for Blockquotes */
    html[data-theme="dark"] blockquote {
      border-left-color: var(--gold);
      background: rgba(0,0,0,0.3);
      color: rgba(255,255,255,0.9);
    }

    /* Dark Mode for Horizontal Rules */
    html[data-theme="dark"] hr {
      border-color: rgba(255,255,255,0.1);
    }

    /* Dark Mode for Selection */
    html[data-theme="dark"] ::selection {
      background: var(--gold);
      color: #111216;
    }

    html[data-theme="dark"] ::-moz-selection {
      background: var(--gold);
      color: #111216;
    }

    /* Remove blur effects from all select elements */
    select, select *, select option, select optgroup {
      filter: none !important;
      backdrop-filter: none !important;
      -webkit-backdrop-filter: none !important;
    }

    /* Ensure select elements are crisp and clear */
    select {
      filter: none !important;
      backdrop-filter: none !important;
      -webkit-backdrop-filter: none !important;
    }

    /* Remove blur from select dropdowns */
    select option {
      filter: none !important;
      backdrop-filter: none !important;
      -webkit-backdrop-filter: none !important;
    }

    /* Remove blur from all form elements */
    input, textarea, select, button, form {
      filter: none !important;
      backdrop-filter: none !important;
      -webkit-backdrop-filter: none !important;
    }

    /* Remove blur from form elements in dark mode */
    html[data-theme="dark"] input,
    html[data-theme="dark"] textarea,
    html[data-theme="dark"] select,
    html[data-theme="dark"] button,
    html[data-theme="dark"] form {
      filter: none !important;
      backdrop-filter: none !important;
      -webkit-backdrop-filter: none !important;
    }

    /* Dark Mode Scrollbar */
    html[data-theme="dark"] ::-webkit-scrollbar {
      width: 12px;
    }

    html[data-theme="dark"] ::-webkit-scrollbar-track {
      background: rgba(0,0,0,0.3);
    }

    html[data-theme="dark"] ::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.2);
      border-radius: 6px;
    }

    html[data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
      background: rgba(255,255,255,0.3);
    }

    /* ===== Enhanced Product Card Styles ===== */

    /* Product Actions Container */
    .product-actions {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-top: 1rem;
      padding-top: 0.75rem;
      border-top: 1px solid rgba(240,194,75,0.2);
    }

    /* Product Action Buttons */
    .product-action-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      font-size: 0.875rem;
      font-weight: 500;
      border-radius: 8px;
      transition: all 0.3s ease;
      text-decoration: none;
      border: 1px solid;
      background: transparent;
      position: relative;
      overflow: hidden;
    }

    .product-action-btn .btn-icon {
      font-size: 1rem;
      transition: transform 0.3s ease;
    }

    .product-action-btn .btn-text {
      font-size: 0.8rem;
      white-space: nowrap;
    }

    /* View Details Button */
    .view-details-btn {
      color: #3b82f6;
      border-color: #3b82f6;
      background: rgba(59, 130, 246, 0.1);
    }

    .view-details-btn:hover {
      color: #ffffff;
      background: #3b82f6;
      border-color: #3b82f6;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* Copy Link Button */
    .copy-link-btn {
      color: #6b7280;
      border-color: #6b7280;
      background: rgba(107, 114, 128, 0.1);
    }

    .copy-link-btn:hover {
      color: #ffffff;
      background: #6b7280;
      border-color: #6b7280;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    /* Wishlist Button */
    .wishlist-btn {
      color: #ef4444;
      border-color: #ef4444;
      background: rgba(239, 68, 68, 0.1);
    }

    .wishlist-btn:hover {
      color: #ffffff;
      background: #ef4444;
      border-color: #ef4444;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .wishlist-btn.in-wishlist {
      color: #ffffff;
      background: #ef4444;
      border-color: #ef4444;
    }

    .wishlist-btn.in-wishlist .wishlist-icon {
      animation: heartBeat 0.6s ease-in-out;
    }

    @keyframes heartBeat {
      0% { transform: scale(1); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }

    /* Product Action Button Hover Effects */
    .product-action-btn:hover .btn-icon {
      transform: scale(1.1);
    }

    /* ===== Product Modal Styles ===== */

    .product-modal-image {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      background: var(--glass);
      border: 1px solid var(--border);
    }

    .product-modal-image img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .product-modal-image:hover img {
      transform: scale(1.05);
    }

    .product-modal-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 0.5rem;
      line-height: 1.3;
    }

    .product-modal-brand {
      font-size: 1rem;
      color: var(--gold);
      font-weight: 600;
      margin-bottom: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .product-modal-price {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 1rem;
    }

    .product-modal-price .original-price {
      font-size: 1.25rem;
      color: var(--muted);
      text-decoration: line-through;
      margin-right: 0.75rem;
    }

    .product-modal-price .sale-price {
      color: #10b981;
      text-shadow: 0 1px 2px rgba(16, 185, 129, 0.2);
    }

    .product-modal-description {
      color: var(--muted);
      line-height: 1.6;
      margin-bottom: 1.5rem;
      max-height: 120px;
      overflow-y: auto;
    }

    .product-modal-actions .btn {
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .product-modal-actions .btn:hover {
      transform: translateY(-2px);
    }

    /* Modal Dark Mode Styles */
    html[data-theme="dark"] .modal-content {
      background: rgba(22, 26, 32, 0.98);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .modal-header {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    html[data-theme="dark"] .modal-title {
      color: #ffffff;
    }

    html[data-theme="dark"] .btn-close {
      filter: invert(1);
    }

    html[data-theme="dark"] .product-modal-image {
      background: rgba(0, 0, 0, 0.3);
      border-color: rgba(255, 255, 255, 0.1);
    }

    html[data-theme="dark"] .product-modal-name {
      color: #ffffff;
    }

    html[data-theme="dark"] .product-modal-brand {
      color: var(--gold);
    }

    html[data-theme="dark"] .product-modal-price {
      color: #ffffff;
    }

    html[data-theme="dark"] .product-modal-description {
      color: rgba(255, 255, 255, 0.8);
    }

    /* ===== Toast Notification Styles ===== */

    .toast {
      background: var(--glass);
      border: 1px solid var(--border);
      border-radius: 12px;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .toast-header {
      background: transparent;
      border-bottom: 1px solid var(--border);
    }

    .toast-body {
      color: var(--text);
      font-weight: 500;
    }

    .toast-success {
      border-left: 4px solid #10b981;
    }

    .toast-success .toast-header {
      color: #10b981;
    }

    .toast-error {
      border-left: 4px solid #ef4444;
    }

    .toast-error .toast-header {
      color: #ef4444;
    }

    .toast-info {
      border-left: 4px solid #3b82f6;
    }

    .toast-info .toast-header {
      color: #3b82f6;
    }

    /* Dark Mode Toast Styles */
    html[data-theme="dark"] .toast {
      background: rgba(22, 26, 32, 0.98);
      border-color: rgba(255, 255, 255, 0.1);
    }

    html[data-theme="dark"] .toast-header {
      border-bottom-color: rgba(255, 255, 255, 0.1);
      color: #ffffff;
    }

    html[data-theme="dark"] .toast-body {
      color: #ffffff;
    }

    /* ===== Responsive Design ===== */

    @media (max-width: 768px) {
      .product-actions {
        gap: 0.375rem;
      }

      .product-action-btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.8rem;
      }

      .product-action-btn .btn-text {
        font-size: 0.75rem;
      }

      .product-modal-image img {
        height: 250px;
      }

      .product-modal-name {
        font-size: 1.25rem;
      }

      .product-modal-price {
        font-size: 1.5rem;
      }

      .product-modal-actions .btn {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
      }
    }

    @media (max-width: 576px) {
      .product-actions {
        flex-direction: column;
        gap: 0.25rem;
      }

      .product-action-btn {
        padding: 0.5rem;
        font-size: 0.75rem;
      }

      .product-action-btn .btn-text {
        display: none;
      }

      .product-action-btn .btn-icon {
        font-size: 1.25rem;
      }

      .product-modal-image img {
        height: 200px;
      }

      .product-modal-name {
        font-size: 1.125rem;
      }

      .product-modal-price {
        font-size: 1.25rem;
      }
    }

    /* ===== Animation Enhancements ===== */

    .product-action-btn {
      animation: fadeInUp 0.6s ease-out;
    }

    .product-action-btn:nth-child(1) { animation-delay: 0.1s; }
    .product-action-btn:nth-child(2) { animation-delay: 0.2s; }
    .product-action-btn:nth-child(3) { animation-delay: 0.3s; }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Loading State for Buttons */
    .product-action-btn.loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .product-action-btn.loading .btn-icon {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  </style>

  @php
    $siteName = $siteName ?? (\App\Models\Setting::get('site.name','AVENUE') ?? 'AVENUE');
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

{{-- ENHANCED NAVBAR --}}
<nav class="navbar navbar-expand-lg vel-nav sticky-top">
  <div class="container py-2">
    {{-- Logo Section - Left for EN, Right for AR --}}
    <a class="navbar-brand fw-bold d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'order-lg-3' : 'order-lg-1' }}" href="/" style="gap:.5rem;">
      @if($logoPath && file_exists(storage_path('app/public/' . $logoPath)))
        <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $siteName }}" style="height:36px; width:auto;">
      @else
        <picture>
          <source srcset="{{ asset('brand/avenue-dark.svg') }}" media="(prefers-color-scheme: dark)">
          <img src="{{ asset('brand/avenue.svg') }}" alt="{{ $siteName }}" style="height:36px; width:auto;" loading="eager" fetchpriority="high">
        </picture>
      @endif
    </a>

    {{-- Mobile Toggle --}}
    <button class="navbar-toggler {{ app()->getLocale() === 'ar' ? 'order-lg-1' : 'order-lg-3' }}" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="mainNav" class="collapse navbar-collapse order-lg-2">
      {{-- Center Navigation Links --}}
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('products.index') }}">
            Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('categories.index') }}">
            Categories
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('brands.index') }}">
            {{ __('common.nav.brands') }}
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('about') }}">
            {{ __('common.nav.about') }}
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('contact') }}">
            {{ __('common.nav.contact') }}
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('wishlist.index') }}">
            {{ __('common.nav.wishlist') }}
          </a>
        </li>
      </ul>

      {{-- Right Actions - Theme & Language Switchers --}}
      <div class="d-flex align-items-center gap-2 {{ app()->getLocale() === 'ar' ? 'order-lg-1' : 'order-lg-3' }}">
        {{-- Theme Switcher --}}
        <div class="theme-switcher">
          <button class="btn btn-sm btn-theme-switcher" type="button" id="velToggleNav" title="{{ __('common.messages.toggle_theme') }}">
            <i class="fas fa-moon" id="themeIcon"></i>
          </button>
        </div>

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
        <p class="footer-description mb-3">
          {{ __('common.pages.refined_storefront') }}
          Discover premium products curated for discerning customers.
        </p>
        <div class="footer-social">
          <div class="social-links">
            @if(isset($settings['social_media']['facebook']) && $settings['social_media']['facebook'])
            <a href="{{ $settings['social_media']['facebook'] }}" target="_blank" class="social-link facebook-link" aria-label="Facebook">
              <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            @endif

            @if(isset($settings['social_media']['instagram']) && $settings['social_media']['instagram'])
            <a href="{{ $settings['social_media']['instagram'] }}" target="_blank" class="social-link instagram-link" aria-label="Instagram">
              <svg class="social-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16.98 0a6.9 6.9 0 0 1 5.08 1.98A6.94 6.94 0 0 1 24 7.02v9.96c0 2.08-.68 3.87-1.98 5.13A7.14 7.14 0 0 1 16.94 24H7.06a7.06 7.06 0 0 1-5.03-1.89A6.96 6.96 0 0 1 0 16.94V7.02C0 2.8 2.8 0 7.02 0h9.96zm.05 2.23H7.06c-1.45 0-2.7.43-3.53 1.25a4.82 4.82 0 0 0-1.02 1.08A4.9 4.9 0 0 0 2.1 7.02v9.92a4.9 4.9 0 0 0 1.44 3.53 4.9 4.9 0 0 0 3.53 1.44h9.88a4.9 4.9 0 0 0 3.53-1.44 4.9 4.9 0 0 0 1.44-3.53V7.02a4.9 4.9 0 0 0-1.44-3.53 4.9 4.9 0 0 0-3.53-1.44zM12 5.76c3.39 0 6.13 2.74 6.13 6.13a6.13 6.13 0 0 1-12.26 0c0-3.39 2.74-6.13 6.13-6.13zm0 2.22a3.91 3.91 0 0 0-3.9 3.9 3.91 3.91 0 0 0 3.9 3.9 3.91 3.91 0 0 0 3.9-3.9 3.91 3.91 0 0 0-3.9-3.9zm6.44-3.53a1.68 1.68 0 0 1 0 3.36 1.68 1.68 0 0 1-3.36 0 1.68 1.68 0 0 1 3.36 0z"/>
              </svg>
            </a>
            @endif
          </div>
        </div>
      </div>

      <div class="col-6 col-md-2">
        <div class="footer-head">Products</div>
        <ul class="footer-links">
          <li><a class="footer-link" href="{{ route('products.index') }}">All Products</a></li>
          <li><a class="footer-link" href="{{ route('categories.index') }}">Categories</a></li>
          <li><a class="footer-link" href="{{ route('brands.index') }}">Brands</a></li>
          <li><a class="footer-link" href="{{ route('wishlist.index') }}">Wishlist</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2">
        <div class="footer-head">Company</div>
        <ul class="footer-links">
          <li><a class="footer-link" href="{{ route('about') }}">About Us</a></li>
          <li><a class="footer-link" href="{{ route('contact') }}">Contact</a></li>
          <li><a class="footer-link" href="{{ route('faq') }}">FAQ</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2">
        <div class="footer-head">Legal</div>
        <ul class="footer-links">
          <li><a class="footer-link" href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
          <li><a class="footer-link" href="{{ route('terms-of-service') }}">Terms of Service</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2">
        <div class="footer-head">Sitemap</div>
        <ul class="footer-links">
          <li><a class="footer-link" href="/">Home</a></li>
          <li><a class="footer-link" href="{{ route('products.index') }}">Products</a></li>
          <li><a class="footer-link" href="{{ route('categories.index') }}">Categories</a></li>
          <li><a class="footer-link" href="{{ route('brands.index') }}">Brands</a></li>
          <li><a class="footer-link" href="{{ route('about') }}">About</a></li>
          <li><a class="footer-link" href="{{ route('contact') }}">Contact</a></li>
        </ul>
      </div>

      <div class="col-12 col-md-4">
        <div class="footer-head">Newsletter</div>
        <p class="footer-description">Stay updated with our latest products and offers.</p>
        <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
          <div class="input-group">
            <input type="email" class="form-control form-control-sm px-3" placeholder="Enter your email" required>
            <button class="btn btn-sm btn-vel-gold px-3" type="submit">Subscribe</button>
          </div>
        </form>
        <div class="newsletter-benefits mt-2">
          <small class="text-muted">
            • Early access to sales • Exclusive offers • Product updates
          </small>
        </div>
      </div>
    </div>

    <hr class="footer-divider my-4">

    <div class="footer-bottom">
      <div class="row align-items-center">
        <div class="col-12 text-center">
          <div class="copyright-text">
            &copy; {{ date('Y') }} {{ $siteName }} — All rights reserved.
                         <span class="developer-credit">
               Developed with
               <svg class="heart-icon" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                 <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
               </svg>
               by <strong>Eng. Yusuf Mohammad Jojeh</strong>
             </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

{{-- Product Details Modal --}}
<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productDetailsModalLabel">{{ __('common.fields.product_details') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.actions.close') }}"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <!-- Product Image -->
          <div class="col-md-6">
            <div class="product-modal-image">
              <img id="modalProductImage" src="" alt="" class="img-fluid rounded">
            </div>
          </div>

          <!-- Product Details -->
          <div class="col-md-6">
            <div class="product-modal-details">
              <h4 id="modalProductName" class="product-modal-name"></h4>
              <p id="modalProductBrand" class="product-modal-brand"></p>

              <div class="product-modal-price-section">
                <div id="modalProductPrice" class="product-modal-price"></div>
              </div>

              <div id="modalProductDescription" class="product-modal-description"></div>

              <div class="product-modal-actions mt-4">
                <a id="modalProductLink" href="#" class="btn btn-primary btn-lg w-100 mb-3" target="_blank">
                  <i class="fas fa-external-link-alt me-2"></i>
                  {{ __('common.actions.view') }} {{ __('common.nav.products') }}
                </a>

                <div class="row g-2">
                  <div class="col-6">
                    <button type="button" class="btn btn-outline-secondary w-100" id="modalCopyLinkBtn">
                      <i class="fas fa-copy me-2"></i>
                      {{ __('common.actions.copy') }}
                    </button>
                  </div>
                  <div class="col-6">
                    <button type="button" class="btn btn-outline-danger w-100" id="modalWishlistBtn">
                      <i class="fas fa-heart me-2"></i>
                      {{ __('common.nav.wishlist') }}
                    </button>
                  </div>
                </div>

                <div class="row g-2 mt-2">
                  <div class="col-12">
                    <a id="modalWhatsAppBtn" href="#" class="btn btn-success w-100" target="_blank">
                      <i class="fab fa-whatsapp me-2"></i>
                      WhatsApp
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Toast Container for Notifications --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer">
  <!-- Toast notifications will be dynamically inserted here -->
</div>

{{-- Floating WhatsApp FAB --}}
<a class="wa-fab" href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener" aria-label="{{ __('common.messages.chat_on_whatsapp') }}">
  <span class="wa-icon" aria-hidden="true"></span> WhatsApp
</a>

{{-- Bootstrap JS with CDN Fallback --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
  // CDN Fallback for Bootstrap
  if (typeof bootstrap === 'undefined') {
    document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"><\/script>');
  }
</script>

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
  document.addEventListener('mousedown', e => {
    const btn = e.target.closest('.btn-vel-gold');
    if (!btn) return;
    btn.classList.add('pressed');
  });

  document.addEventListener('mouseup', () => {
    document.querySelectorAll('.btn-vel-gold.pressed').forEach(b => b.classList.remove('pressed'));
  });

  // subtle tilt for luxe-card
  document.addEventListener('mousemove', e => {
    document.querySelectorAll('.luxe-card').forEach(card => {
      const r = card.getBoundingClientRect();
      if (e.clientX < r.left - 20 || e.clientX > r.right + 20 || e.clientY < r.top - 20 || e.clientY > r.bottom + 20) {
        card.style.transform = ''; // reset (keep hover translate via :hover)
        return;
      }
      const rx = ((e.clientY - r.top) / r.height - 0.5) * 2; // -1..1
      const ry = ((e.clientX - r.left) / r.width - 0.5) * 2;
      card.style.transform = `rotateX(${(-rx * 2)}deg) rotateY(${(ry * 2)}deg) translateY(-6px)`;
    });
  });

  // reveal on scroll
  const io = new IntersectionObserver(entries => {
    entries.forEach(x => {
      if (x.isIntersecting) {
        x.target.classList.add('visible');
        io.unobserve(x.target);
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal').forEach(el => io.observe(el));
})();

// Enhanced Navigation Features
document.addEventListener('DOMContentLoaded', function() {
  // Update theme icon based on current theme
  function updateThemeIcon() {
    const themeIcon = document.getElementById('themeIcon');
    if (themeIcon) {
      const currentTheme = root.getAttribute('data-theme');
      if (currentTheme === 'dark') {
        themeIcon.className = 'fas fa-sun';
        themeIcon.title = '{{ __("common.messages.switch_to_light_mode") }}';
      } else {
        themeIcon.className = 'fas fa-moon';
        themeIcon.title = '{{ __("common.messages.switch_to_dark_mode") }}';
      }
    }
  }

  // Initialize theme icon
  updateThemeIcon();

  // Update icon when theme changes
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
        updateThemeIcon();
      }
    });
  });
  observer.observe(root, { attributes: true });

  // Language switcher enhancement
  const languageDropdown = document.getElementById('languageDropdown');
  if (languageDropdown) {
    // Add click animation
    languageDropdown.addEventListener('click', function() {
      this.style.transform = 'scale(0.95)';
      setTimeout(() => {
        this.style.transform = '';
      }, 150);
    });
  }

  // Add smooth scroll for navigation links
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

  // Add navbar scroll effect
  const navbar = document.querySelector('.vel-nav');
  if (navbar) {
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

      if (scrollTop > 100) {
        navbar.style.background = 'rgba(255, 255, 255, 0.95)';
        navbar.style.backdropFilter = 'blur(20px)';
        navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
      } else {
        navbar.style.background = '';
        navbar.style.backdropFilter = '';
        navbar.style.boxShadow = '';
      }

      lastScrollTop = scrollTop;
    });
  }

  // Add hover effects for navigation links
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-2px)';
    });

    link.addEventListener('mouseleave', function() {
      this.style.transform = '';
    });
  });
});
</script>

{{-- Enhanced Product Cards JavaScript --}}
<script src="{{ asset('js/enhanced-product-cards.js') }}"></script>

@stack('scripts')
</body>
</html>
