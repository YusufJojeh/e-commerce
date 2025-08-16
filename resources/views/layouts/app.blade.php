<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $siteName ?? 'MyStore' }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-img-overlay.bg-dim { background: linear-gradient(180deg, rgba(0,0,0,.0), rgba(0,0,0,.45)); }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="/">{{ $siteName ?? 'MyStore' }}</a>
  </div>
</nav>

@yield('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
