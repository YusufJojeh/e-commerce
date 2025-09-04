<div class="product-card h-100">
  @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
    <div class="sale-badge">
      {{ round((($p->price - $p->sale_price) / $p->price) * 100) }}% OFF
    </div>
  @endif

  <div class="thumb">
    <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}" loading="lazy">
  </div>

  <div class="card-body">
    <div class="product-name">{{ $p->name }}</div>

    @if($p->brand)
      <div class="brand-name">{{ $p->brand->name }}</div>
    @endif

    <!-- Product Rating -->
    <div class="product-rating">
      <div class="rating-stars">
        @for($i = 1; $i <= 5; $i++)
          <span class="star {{ $i <= 4 ? '' : 'empty' }}">★</span>
        @endfor
      </div>
      <span class="rating-text">(4.3)</span>
    </div>

    <!-- Product Tags -->
    <div class="product-tags">
      <span class="product-tag">Quality</span>
      <span class="product-tag">Value</span>
    </div>

    <!-- Stock Status -->
    <div class="stock-status">
      <span class="stock-indicator"></span>
      <span class="stock-text">In Stock</span>
    </div>

    <div class="price-section">
      @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
        <div class="price">
          <span class="original-price">${{ number_format($p->price, 2) }}</span>
          <span class="sale-price">${{ number_format($p->sale_price, 2) }}</span>
        </div>
      @else
        <div class="price">
          <span class="sale-price">${{ number_format($p->price, 2) }}</span>
        </div>
      @endif
    </div>

    <!-- WYW Button - Why You Want -->
    <a href="{{ route('products.show', $p->slug) }}" class="wyw-button">
      Why You Want This
    </a>
  </div>
</div>
