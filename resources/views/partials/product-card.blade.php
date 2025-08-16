<div class="card h-100">
    @if($p->primaryImage)
      <img src="{{ asset('storage/'.$p->primaryImage->path) }}" class="card-img-top" alt="{{ $p->primaryImage->alt }}">
    @endif
    <div class="card-body">
      <div class="small text-muted d-flex justify-content-between">
        <span>{{ $p->category?->name }}</span>
        @if($p->brand)<span>{{ $p->brand->name }}</span>@endif
      </div>
      <div class="fw-semibold">{{ $p->name }}</div>
      <div class="mt-1">
        @if($p->sale_price)
          <span class="fw-bold">{{ number_format($p->sale_price, 2) }}</span>
          <del class="text-muted small">{{ number_format($p->price, 2) }}</del>
        @else
          <span class="fw-bold">{{ number_format($p->price, 2) }}</span>
        @endif
      </div>
    </div>
  </div>
