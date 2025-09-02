<a href="{{ route('products.show', $p->slug) }}" class="card product-card h-100 text-decoration-none">
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
        <span class="price-cut me-1">{{ number_format($p->price, 2) }}</span>
        <span class="fw-semibold text-success">{{ number_format($p->sale_price, 2) }}</span>
      @else
        <span class="fw-semibold">{{ number_format($p->price, 2) }}</span>
      @endif
    </div>
  </div>
</a>
