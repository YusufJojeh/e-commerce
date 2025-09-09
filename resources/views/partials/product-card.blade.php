<div class="card product-card h-100"
     data-product-id="{{ $p->id }}"
     data-product-name="{{ $p->name }}"
     data-product-price="{{ $p->price }}"
     data-product-sale-price="{{ $p->sale_price ?? '' }}"
     data-product-image="{{ $p->primary_image_url }}"
     data-product-description="{{ $p->description ?? $p->short_description ?? '' }}"
     data-product-url="{{ route('products.show', $p->slug) }}"
     data-product-brand="{{ $p->brand->name ?? '' }}">

  {{-- Sale Badge --}}
  @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
    <div class="sale-badge">
      {{ round((($p->price - $p->sale_price) / $p->price) * 100) }}% OFF
    </div>
  @endif

  {{-- Product Image --}}
  <div class="product-image-wrapper">
    <div class="product-image">
      <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}" loading="lazy">
      <div class="image-overlay">
        <div class="quick-actions">
          <button type="button" class="quick-action-btn view-details-btn" data-bs-toggle="modal" data-bs-target="#productDetailsModal" title="{{ __('common.messages.view_details') }}">
            <i class="fas fa-eye"></i>
          </button>
          <button type="button" class="quick-action-btn wishlist-btn" data-product-id="{{ $p->id }}" title="{{ __('common.messages.add_to_wishlist') }}">
            <i class="fas fa-heart"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Card Body --}}
  <div class="card-body d-flex flex-column">
    {{-- Brand --}}
    @if($p->brand)
      <div class="product-brand">{{ $p->brand->name }}</div>
    @endif

    {{-- Product Name --}}
    <h5 class="product-name">{{ $p->name }}</h5>

    {{-- Rating --}}
    <div class="product-rating">
      <div class="rating-stars">
        @for($i = 1; $i <= 5; $i++)
          <span class="star {{ $i <= 4 ? 'filled' : 'empty' }}">★</span>
        @endfor
      </div>
      <span class="rating-text">(4.3)</span>
    </div>

    {{-- Price Section --}}
    <div class="price-section mt-auto">
      @if(!is_null($p->sale_price) && $p->sale_price > 0 && $p->sale_price < $p->price)
        <div class="price-row">
          <span class="original-price">${{ number_format($p->price, 2) }}</span>
          <span class="sale-price">${{ number_format($p->sale_price, 2) }}</span>
        </div>
      @else
        <div class="price-row">
          <span class="current-price">${{ number_format($p->price, 2) }}</span>
        </div>
      @endif
    </div>

    {{-- Action Buttons --}}
    <div class="product-actions">
      <a href="{{ route('products.show', $p->slug) }}" class="btn btn-primary btn-sm view-details-btn" title="{{ __('common.messages.view_details') }}">
        <i class="fas fa-eye"></i>
      </a>
      <button type="button" class="btn btn-outline-secondary btn-sm copy-link-btn" data-product-url="{{ route('products.show', $p->slug) }}" title="{{ __('common.messages.copy_link') }}">
        <i class="fas fa-copy"></i>
      </button>
      <button type="button" class="btn btn-outline-danger btn-sm wishlist-btn" data-product-id="{{ $p->id }}" title="{{ __('common.messages.add_to_wishlist') }}">
        <i class="fas fa-heart"></i>
      </button>
    </div>
  </div>
</div>

<style>
/* ===== Enhanced Product Card Styles ===== */
.product-card {
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s ease;
  background: var(--glass);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  position: relative;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 32px rgba(0,0,0,0.15);
  border-color: var(--gold);
}

/* Sale Badge */
.sale-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: linear-gradient(135deg, #ff4757, #ff3838);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  z-index: 10;
  box-shadow: 0 2px 8px rgba(255,71,87,0.3);
}

/* Product Image */
.product-image-wrapper {
  position: relative;
  overflow: hidden;
  aspect-ratio: 1/1;
  background: var(--surface);
}

.product-image {
  width: 100%;
  height: 100%;
  position: relative;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
  transform: scale(1.05);
}

/* Image Overlay */
.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.product-card:hover .image-overlay {
  opacity: 1;
}

.quick-actions {
  display: flex;
  gap: 0.75rem;
}

.quick-action-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: none;
  background: var(--gold);
  color: #111216;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  cursor: pointer;
}

.quick-action-btn:hover {
  background: #f59e0b;
  transform: scale(1.1);
}

/* Card Body */
.card-body {
  padding: 1.25rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* Product Brand */
.product-brand {
  font-size: 0.8rem;
  color: var(--muted);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.5rem;
}

/* Product Name */
.product-name {
  font-size: 1rem;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 0.75rem;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Rating */
.product-rating {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.rating-stars {
  display: flex;
  gap: 0.125rem;
}

.star {
  font-size: 0.875rem;
  color: #fbbf24;
}

.star.empty {
  color: var(--border);
}

.rating-text {
  font-size: 0.8rem;
  color: var(--muted);
  font-weight: 600;
}

/* Price Section */
.price-section {
  margin-top: auto;
  margin-bottom: 1rem;
}

.price-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.original-price {
  font-size: 0.9rem;
  color: var(--muted);
  text-decoration: line-through;
  font-weight: 500;
}

.sale-price, .current-price {
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--gold);
}

/* Action Buttons */
.product-actions {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 0.5rem;
  margin-top: auto;
}

.product-actions .btn {
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  padding: 0.6rem;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 40px;
  text-decoration: none;
  width: 100%;
}

.product-actions .btn-primary {
  background: linear-gradient(135deg, var(--gold), #f59e0b);
  border: none;
  color: #111216;
}

.product-actions .btn-primary:hover {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(240,194,75,0.3);
}

.product-actions .btn-outline-secondary {
  border-color: var(--border);
  color: var(--text);
}

.product-actions .btn-outline-secondary:hover {
  background: var(--surface);
  border-color: var(--gold);
  color: var(--gold);
}

.product-actions .btn-outline-danger {
  border-color: #ef4444;
  color: #ef4444;
}

.product-actions .btn-outline-danger:hover {
  background: #ef4444;
  color: white;
}

/* Wishlist Active State */
.wishlist-btn.in-wishlist {
  background: #ef4444 !important;
  border-color: #ef4444 !important;
  color: white !important;
}

.wishlist-btn.in-wishlist:hover {
  background: #dc2626 !important;
  border-color: #dc2626 !important;
}

/* Responsive Design */
@media (max-width: 768px) {
  .product-card {
    border-radius: 12px;
  }

  .card-body {
    padding: 1rem;
  }

  .product-name {
    font-size: 0.9rem;
  }

  .sale-price, .current-price {
    font-size: 1.1rem;
  }

  .product-actions .btn {
    font-size: 0.8rem;
    padding: 0.5rem;
    min-width: 35px;
  }
}

@media (max-width: 576px) {
  .quick-actions {
    gap: 0.5rem;
  }

  .quick-action-btn {
    width: 35px;
    height: 35px;
  }

  .product-actions {
    grid-template-columns: 1fr 1fr 1fr;
    gap: 0.4rem;
  }

  .product-actions .btn {
    padding: 0.4rem;
    min-width: 30px;
  }
}

/* Dark Mode Enhancements */
html[data-theme="dark"] .product-card {
  background: rgba(22,26,32,0.8);
  border-color: rgba(255,255,255,0.1);
}

html[data-theme="dark"] .product-image-wrapper {
  background: rgba(17,18,22,0.5);
}

html[data-theme="dark"] .image-overlay {
  background: rgba(0,0,0,0.8);
}

/* Animation */
.product-card {
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* List View Styles */
.list-view .product-card {
  display: flex;
  flex-direction: row;
  height: auto;
}

.list-view .product-image-wrapper {
  width: 200px;
  height: 200px;
  flex-shrink: 0;
}

.list-view .card-body {
  flex: 1;
  padding: 1.5rem;
}

.list-view .product-actions {
  grid-template-columns: 1fr 1fr 1fr;
  margin-top: 1rem;
}

@media (max-width: 768px) {
  .list-view .product-card {
    flex-direction: column;
  }

  .list-view .product-image-wrapper {
    width: 100%;
    height: 250px;
  }
}
</style>
