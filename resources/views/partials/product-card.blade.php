@php
  $img = optional($p->primaryImage)->path ? asset('storage/'. $p->primaryImage->path) : asset('storage/products/sample.jpg');
@endphp

<a href="{{ route('products.show', $p->slug) }}" class="card h-100 text-decoration-none">
  <img src="{{ $img }}" class="card-img-top" alt="{{ $p->name }}" loading="lazy">
  <div class="card-body">
    <div class="small text-muted">
      @if($p->brand) <span>{{ $p->brand->name }}</span> @endif
      @if($p->category) <span>• {{ $p->category->name }}</span> @endif
    </div>
    <div class="fw-semibold">{{ $p->name }}</div>
    <div class="mt-1">
      @if($p->sale_price)
        <span class="fw-bold">{{ number_format($p->sale_price, 2) }}</span>
        <span class="text-muted text-decoration-line-through small">{{ number_format($p->price, 2) }}</span>
      @else
        <span class="fw-bold">{{ number_format($p->price, 2) }}</span>
      @endif
    </div>
  </div>
</a>
