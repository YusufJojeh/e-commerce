@extends('layouts.app')

@section('title', 'Categories')

@push('styles')
<style>
  .category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
  }

  .category-card {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem;
    text-decoration: none;
    color: var(--text);
    transition: all 0.3s ease;
    text-align: center;
  }

  .category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    color: var(--text);
    text-decoration: none;
  }

  .category-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 1rem;
    border: 2px solid var(--gold);
    box-shadow: 0 4px 16px rgba(240,194,75,0.2);
  }

  .category-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .category-description {
    color: var(--muted);
    font-size: 0.9rem;
    margin-bottom: 1rem;
  }

  .category-stats {
    display: flex;
    justify-content: center;
    gap: 1rem;
    font-size: 0.85rem;
    color: var(--muted);
  }

  .category-stats span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }

  .page-header {
    text-align: center;
    margin-bottom: 3rem;
  }

  .page-description {
    color: var(--muted);
    max-width: 600px;
    margin: 0 auto;
  }
</style>
@endpush

@section('content')
<div class="container py-4">
  {{-- Page Header --}}
  <div class="page-header">
    <h1 class="h2 mb-2">Browse Categories</h1>
    <p class="page-description">
      Explore our product categories to find exactly what you're looking for.
      Each category is carefully curated to help you discover the best products.
    </p>
  </div>

  {{-- Categories Grid --}}
  <div class="category-grid">
    @forelse($categories as $category)
      <a href="{{ route('categories.show', $category->slug) }}" class="category-card">
        @if($category->image_path)
          <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="category-image">
        @else
          <div class="category-image" style="background: var(--surface); display: flex; align-items: center; justify-content: center; color: var(--muted);">
            📁
          </div>
        @endif

        <div class="category-name">{{ $category->name }}</div>

        @if($category->description)
          <div class="category-description">{{ Str::limit($category->description, 80) }}</div>
        @endif

        <div class="category-stats">
          <span>
            <span>📦</span>
            {{ $category->products_count ?? 0 }} products
          </span>
          @if($category->children_count > 0)
            <span>
              <span>📂</span>
              {{ $category->children_count }} subcategories
            </span>
          @endif
        </div>
      </a>
    @empty
      <div class="col-12">
        <div class="alert alert-info text-center">
          <h5>No categories found</h5>
          <p class="mb-0">Categories will appear here once they are added to the system.</p>
        </div>
      </div>
    @endforelse
  </div>

  {{-- Browse All Products Link --}}
  <div class="text-center mt-5">
    <a href="{{ route('products.index') }}" class="btn btn-vel-gold">
      Browse All Products
    </a>
  </div>
</div>
@endsection
