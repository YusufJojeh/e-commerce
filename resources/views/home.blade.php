@extends('layouts.app')

@section('title', $siteName ?? 'Home')

@push('styles')
<style>
  /* Crystal and Glass Effects */
  .crystal-card {
    background: linear-gradient(135deg,
      rgba(255,255,255,0.1) 0%,
      rgba(255,255,255,0.05) 50%,
      rgba(255,255,255,0.02) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.1),
      inset 0 1px 0 rgba(255,255,255,0.2);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .crystal-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
      transparent,
      rgba(255,255,255,0.1),
      transparent);
    transition: left 0.6s ease;
  }

  .crystal-card:hover::before {
    left: 100%;
  }

  .crystal-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.15),
      0 0 0 1px rgba(255,255,255,0.3),
      inset 0 1px 0 rgba(255,255,255,0.3);
  }

  /* Hero Section */
  .hero-section {
    position: relative;
    min-height: 80vh;
    display: flex;
    align-items: center;
    overflow: hidden;
  }

  .hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background:
      radial-gradient(circle at 20% 80%, rgba(240,194,75,0.15) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(120,119,198,0.1) 0%, transparent 50%),
      radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 0%, transparent 50%);
    animation: heroFloat 20s ease-in-out infinite;
  }

  @keyframes heroFloat {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(1deg); }
    66% { transform: translateY(10px) rotate(-1deg); }
  }

  /* Floating Elements */
  .floating-element {
    position: absolute;
    opacity: 0.6;
    animation: float 6s ease-in-out infinite;
  }

  .floating-element:nth-child(2) { animation-delay: -2s; }
  .floating-element:nth-child(3) { animation-delay: -4s; }

  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
  }

  /* Modern Product Cards - UI/UX Expert Design */
  .product-card {
    background: linear-gradient(135deg,
      rgba(255,255,255,0.95) 0%,
      rgba(255,255,255,0.85) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(240,194,75,0.15);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.08),
      0 4px 16px rgba(0,0,0,0.04);
  }

  .product-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow:
      0 24px 64px rgba(0,0,0,0.15),
      0 12px 32px rgba(240,194,75,0.2);
    border-color: rgba(240,194,75,0.4);
  }

  /* Card Image Container */
  .product-card .thumb {
    position: relative;
    aspect-ratio: 1/1;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    position: relative;
  }

  .product-card .thumb::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg,
      transparent 0%,
      rgba(255,255,255,0.1) 50%,
      transparent 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
  }

  .product-card:hover .thumb::before {
    opacity: 1;
  }

  .product-card .thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .product-card:hover .thumb img {
    transform: scale(1.08);
  }

  /* Sale Badge Enhancement */
  .sale-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 700;
    z-index: 3;
    box-shadow:
      0 4px 16px rgba(220, 53, 69, 0.4),
      0 2px 8px rgba(0,0,0,0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
  }

  /* Product Info Container */
  .product-card .card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: 100%;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    position: relative;
  }

  /* Product Name */
  .product-card .product-name {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    font-weight: 700;
    font-size: 1.1rem;
    line-height: 1.4;
    color: #2d3748;
    margin-bottom: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 3rem;
    transition: color 0.3s ease;
  }

  .product-card:hover .product-name {
    color: #1a202c;
  }

  /* Brand Name */
  .product-card .brand-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #718096;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.8;
    transition: all 0.3s ease;
  }

  .product-card:hover .brand-name {
    opacity: 1;
    color: #4a5568;
  }

  /* Price Section */
  .product-card .price-section {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(226, 232, 240, 0.8);
    position: relative;
  }

  .product-card .price-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 40px;
    height: 2px;
    background: linear-gradient(90deg, #f0c24b, transparent);
    border-radius: 1px;
  }

  .product-card .price {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
  }

  .product-card .original-price {
    font-size: 0.875rem;
    color: #a0aec0;
    text-decoration: line-through;
    opacity: 0.7;
  }

  .product-card .sale-price {
    font-size: 1.5rem;
    font-weight: 800;
    color: #38a169;
    text-shadow: 0 1px 2px rgba(56, 161, 105, 0.1);
  }

  /* WYW Button - Why You Want */
  .wyw-button {
    width: 100%;
    padding: 0.875rem 1.5rem;
    background: linear-gradient(135deg, #f0c24b, #e2b13c);
    color: #1a202c;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow:
      0 4px 16px rgba(240, 194, 75, 0.3),
      0 2px 8px rgba(0,0,0,0.1);
  }

  .wyw-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
      transparent,
      rgba(255,255,255,0.3),
      transparent);
    transition: left 0.5s ease;
  }

  .wyw-button:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #e2b13c, #d4a62d);
    box-shadow:
      0 8px 24px rgba(240, 194, 75, 0.4),
      0 4px 16px rgba(0,0,0,0.15);
    color: #1a202c;
  }

  .wyw-button:hover::before {
    left: 100%;
  }

  .wyw-button:active {
    transform: translateY(0);
    box-shadow:
      0 4px 16px rgba(240, 194, 75, 0.3),
      0 2px 8px rgba(0,0,0,0.1);
  }

  /* Product Rating */
  .product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
  }

  .rating-stars {
    display: flex;
    gap: 2px;
  }

  .star {
    color: #fbbf24;
    font-size: 0.875rem;
  }

  .star.empty {
    color: #e2e8f0;
  }

  .rating-text {
    font-size: 0.8rem;
    color: #718096;
    font-weight: 500;
  }

  /* Product Tags */
  .product-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
  }

  .product-tag {
    padding: 4px 8px;
    background: rgba(240, 194, 75, 0.1);
    color: #d69e2e;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(240, 194, 75, 0.2);
  }

  /* Stock Status */
  .stock-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    font-size: 0.8rem;
  }

  .stock-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #38a169;
  }

  .stock-indicator.low {
    background: #f59e0b;
  }

  .stock-indicator.out {
    background: #e53e3e;
  }

  .stock-text {
    color: #718096;
    font-weight: 500;
  }

  /* Dark Mode Product Cards */
  html[data-theme="dark"] .product-card {
    background: linear-gradient(135deg,
      rgba(26, 32, 44, 0.95) 0%,
      rgba(45, 55, 72, 0.85) 100%);
    border-color: rgba(255,255,255,0.1);
    box-shadow:
      0 8px 32px rgba(0,0,0,0.3),
      0 4px 16px rgba(0,0,0,0.2);
  }

  html[data-theme="dark"] .product-card:hover {
    box-shadow:
      0 24px 64px rgba(0,0,0,0.4),
      0 12px 32px rgba(240,194,75,0.3);
  }

  html[data-theme="dark"] .product-card .card-body {
    background: rgba(0,0,0,0.4);
  }

  html[data-theme="dark"] .product-card .product-name {
    color: #f7fafc;
  }

  html[data-theme="dark"] .product-card:hover .product-name {
    color: #ffffff;
  }

  html[data-theme="dark"] .product-card .brand-name {
    color: #a0aec0;
  }

  html[data-theme="dark"] .product-card:hover .brand-name {
    color: #cbd5e0;
  }

  html[data-theme="dark"] .product-card .price-section {
    border-top-color: rgba(255,255,255,0.1);
  }

  html[data-theme="dark"] .product-card .original-price {
    color: #718096;
  }

  html[data-theme="dark"] .product-card .sale-price {
    color: #68d391;
  }

  html[data-theme="dark"] .rating-text {
    color: #a0aec0;
  }

  html[data-theme="dark"] .stock-text {
    color: #a0aec0;
  }

  html[data-theme="dark"] .product-tag {
    background: rgba(240, 194, 75, 0.15);
    color: #f6e05e;
    border-color: rgba(240, 194, 75, 0.3);
  }

  /* Dark Mode Hero Section */
  html[data-theme="dark"] .hero-section {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      rgba(17,18,22,0.95) 100%);
  }

  html[data-theme="dark"] .hero-bg {
    background:
      radial-gradient(circle at 20% 80%, rgba(240,194,75,0.12) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(120,119,198,0.08) 0%, transparent 50%),
      radial-gradient(circle at 40% 40%, rgba(255,255,255,0.03) 0%, transparent 50%);
  }

  /* Dark Mode CTA Section */
  html[data-theme="dark"] .cta-section {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.15) 0%,
      rgba(17,18,22,0.98) 100%);
  }

  /* Dark Mode Crystal Cards */
  html[data-theme="dark"] .crystal-card {
    background: linear-gradient(135deg,
      rgba(255,255,255,0.05) 0%,
      rgba(255,255,255,0.02) 50%,
      rgba(255,255,255,0.01) 100%);
    border-color: rgba(255,255,255,0.1);
  }

    html[data-theme="dark"] .crystal-card:hover {
    border-color: rgba(255,255,255,0.2);
    box-shadow: 0 20px 40px rgba(0,0,0,0.4);
  }

  /* Sale Badge */
  .sale-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 2;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  }

  /* Dark Mode Sale Badge */
  html[data-theme="dark"] .sale-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
  }

  /* Product Card Thumb */
  .product-card .thumb {
    position: relative;
    aspect-ratio: 1/1;
    overflow: hidden;
    background: var(--surface);
  }

  .product-card .thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .product-card:hover .thumb img {
    transform: scale(1.05);
  }

  /* Dark Mode Thumb */
  html[data-theme="dark"] .product-card .thumb {
    background: rgba(0,0,0,0.3);
  }

  /* Section Titles Dark Mode */
  html[data-theme="dark"] .section-title {
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
  }

  /* Lead Text Dark Mode */
  html[data-theme="dark"] .lead {
    color: rgba(255,255,255,0.9);
  }

  /* Text White Override for Dark Mode */
  html[data-theme="dark"] .text-white {
    color: #ffffff !important;
  }

  /* Professional UI/UX Animation Keyframes - Eye-Friendly */
  @keyframes subtle-float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-2px); }
  }

  @keyframes gentle-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
  }
    box-shadow:
      0 8px 32px rgba(0,0,0,0.1),
      inset 0 1px 0 rgba(240,194,75,0.2);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    height: 100%;
  }

  .product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      transparent 50%,
      rgba(120,119,198,0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .product-card:hover::before {
    opacity: 1;
  }

  .product-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.15),
      0 0 0 1px rgba(240,194,75,0.4),
      inset 0 1px 0 rgba(240,194,75,0.3);
  }

  .product-card .thumb {
    aspect-ratio: 1/1;
    background: var(--surface);
    border-radius: 24px 24px 0 0;
    overflow: hidden;
    position: relative;
  }

  .product-card .thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .product-card:hover .thumb img {
    transform: scale(1.1);
  }

  .product-card .thumb::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg,
      transparent 30%,
      rgba(255,255,255,0.1) 50%,
      transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
  }

  .product-card:hover .thumb::after {
    transform: translateX(100%);
  }

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

  /* Sale Badge */
  .sale-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    z-index: 2;
    animation: pulse 2s infinite;
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

  /* Professional UI/UX Product Card Hover Effects - Eye-Friendly */
  .product-card:hover .product-name {
    color: #1a202c;
    transform: translateY(-1px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-shadow: 0 1px 2px rgba(0,0,0,0.05);
  }

  .product-card:hover .brand-name {
    opacity: 1;
    color: #d69e2e;
    transform: translateX(3px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
  }

  .product-card:hover .price-section {
    border-top-color: rgba(240,194,75,0.4);
    transition: border-color 0.3s ease;
  }

  .product-card:hover .price-section::before {
    width: 60px;
    transition: width 0.3s ease;
  }

  .product-card:hover .product-tags .product-tag {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(240,194,75,0.2);
    transition: all 0.3s ease;
  }

  .product-card:hover .rating-stars .star {
    transform: scale(1.1);
    transition: transform 0.2s ease;
  }

  .product-card:hover .stock-status {
    transform: translateX(2px);
    transition: transform 0.3s ease;
  }

  .product-card:hover .price-section {
    border-top-color: rgba(255,215,0,0.6);
    transition: border-color 0.3s ease;
    box-shadow: 0 0 10px rgba(255,215,0,0.3);
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

  /* Category Cards */
  .category-card {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      rgba(120,119,198,0.1) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(240,194,75,0.3);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.1),
      inset 0 1px 0 rgba(240,194,75,0.2);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
  }

  .category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg,
      rgba(240,194,75,0.2) 0%,
      transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .category-card:hover::before {
    opacity: 1;
  }

  .category-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.15),
      0 0 0 1px rgba(240,194,75,0.4),
      inset 0 1px 0 rgba(240,194,75,0.3);
  }

  /* Offer Cards */
  .offer-card {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      rgba(120,119,198,0.1) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(240,194,75,0.3);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.1),
      inset 0 1px 0 rgba(240,194,75,0.2);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
  }

  .offer-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg,
      transparent 30%,
      rgba(240,194,75,0.1) 50%,
      transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
  }

  .offer-card:hover::before {
    transform: translateX(100%);
  }

  .offer-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.15),
      0 0 0 1px rgba(240,194,75,0.4),
      inset 0 1px 0 rgba(240,194,75,0.3);
  }

  /* Section Titles */
  .section-title {
    font-family: 'Georgia', serif;
    font-weight: 700;
    font-size: 2rem;
    background: linear-gradient(135deg, var(--text) 0%, var(--gold) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
    margin-bottom: 2rem;
    text-align: center;
  }

  .section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--gold), transparent);
    border-radius: 2px;
  }

  /* Carousel Enhancements */
  .carousel-item {
    border-radius: 24px;
    overflow: hidden;
  }

  .carousel-img {
    height: clamp(300px, 50vw, 600px);
    width: 100%;
    object-fit: cover;
    filter: saturate(1.1) contrast(1.05);
  }

  .carousel-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg,
      rgba(0,0,0,0) 0%,
      rgba(0,0,0,0.3) 50%,
      rgba(0,0,0,0.7) 100%);
  }

  .carousel-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2rem;
    background: linear-gradient(180deg,
      transparent 0%,
      rgba(0,0,0,0.8) 100%);
  }

  /* Stats Section */
  .stats-section {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.1) 0%,
      rgba(120,119,198,0.1) 100%);
    border-radius: 24px;
    padding: 3rem 0;
    margin: 4rem 0;
  }

  .stat-item {
    text-align: center;
    padding: 1rem;
  }

  .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--gold), #ff6b6b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .stat-label {
    color: var(--muted);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  /* CTA Section */
  .cta-section {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.15) 0%,
      rgba(120,119,198,0.15) 100%);
    border-radius: 24px;
    padding: 4rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
  }

  /* Animations */
  .fade-in-up {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .fade-in-up.visible {
    opacity: 1;
    transform: translateY(0);
  }

  .scale-in {
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .scale-in.visible {
    opacity: 1;
    transform: scale(1);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .hero-section {
      min-height: 60vh;
    }

    .section-title {
      font-size: 1.5rem;
    }

    .stat-number {
      font-size: 2rem;
    }
  }

  /* Dark mode enhancements - Unified Crystal Card Styling */
  html[data-theme="dark"] .crystal-card,
  html[data-theme="dark"] .product-card,
  html[data-theme="dark"] .category-card,
  html[data-theme="dark"] .offer-card {
    background: linear-gradient(135deg,
      rgba(240,194,75,0.15) 0%,
      rgba(120,119,198,0.15) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(240,194,75,0.3);
    border-radius: 24px;
    box-shadow:
      0 8px 32px rgba(0,0,0,0.3),
      inset 0 1px 0 rgba(240,194,75,0.2);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  html[data-theme="dark"] .crystal-card::before,
  html[data-theme="dark"] .product-card::before,
  html[data-theme="dark"] .category-card::before,
  html[data-theme="dark"] .offer-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
      transparent,
      rgba(240,194,75,0.2),
      transparent);
    transition: left 0.6s ease;
  }

  html[data-theme="dark"] .crystal-card:hover::before,
  html[data-theme="dark"] .product-card:hover::before,
  html[data-theme="dark"] .category-card:hover::before,
  html[data-theme="dark"] .offer-card:hover::before {
    left: 100%;
  }

  html[data-theme="dark"] .crystal-card:hover,
  html[data-theme="dark"] .product-card:hover,
  html[data-theme="dark"] .category-card:hover,
  html[data-theme="dark"] .offer-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow:
      0 20px 40px rgba(0,0,0,0.4),
      0 0 0 1px rgba(240,194,75,0.4),
      inset 0 1px 0 rgba(240,194,75,0.3);
  }

  /* Dark mode specific card enhancements */
  html[data-theme="dark"] .product-card .thumb {
    background: rgba(240,194,75,0.1);
    border-radius: 24px 24px 0 0;
  }

  html[data-theme="dark"] .category-card img {
    border-radius: 24px 24px 0 0;
  }

  /* Dark mode sale badge enhancement */
  html[data-theme="dark"] .sale-badge {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    box-shadow: 0 4px 12px rgba(255,107,107,0.3);
  }

  /* Dark mode card body styling */
  html[data-theme="dark"] .product-card .card-body,
  html[data-theme="dark"] .category-card .card-body {
    background: transparent;
    color: var(--text);
  }

  /* Dark mode button enhancements */
  html[data-theme="dark"] .btn-vel-gold {
    background: linear-gradient(135deg, var(--gold), #ffd700);
    border: 1px solid rgba(240,194,75,0.3);
    box-shadow: 0 4px 12px rgba(240,194,75,0.2);
  }

  html[data-theme="dark"] .btn-vel-gold:hover {
    background: linear-gradient(135deg, #ffd700, var(--gold));
    box-shadow: 0 6px 16px rgba(240,194,75,0.3);
    transform: translateY(-2px);
  }
</style>
@endpush

@section('content')

{{-- ====================== HERO SECTION ====================== --}}
@if(isset($visibility['hero']) && $visibility['hero'] && isset($mainSlide))
  <section class="hero-section reveal">
    <div class="hero-bg"></div>

    {{-- Floating Elements --}}
    <div class="floating-element" style="top: 20%; left: 10%; font-size: 2rem;">
      <svg class="floating-icon" viewBox="0 0 24 24" fill="currentColor" width="32" height="32">
        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
      </svg>
    </div>
    <div class="floating-element" style="top: 60%; right: 15%; font-size: 1.5rem;">
      <svg class="floating-icon" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
      </svg>
    </div>
          <div class="floating-element" style="top: 30%; right: 25%; font-size: 1.8rem;">
        <svg class="floating-icon" viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
      </div>

    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="crystal-card p-4 p-md-5">
            @if(!empty($mainSlide->title))
              <h1 class="display-4 fw-bold mb-3" style="background: linear-gradient(135deg, var(--text) 0%, var(--gold) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                {{ $mainSlide->title }}
              </h1>
            @endif
            @if(!empty($mainSlide->subtitle))
              <p class="lead mb-4" style="color: var(--muted);">
                {{ $mainSlide->subtitle }}
              </p>
            @endif
            @if(!empty($mainSlide->cta_url))
              <a href="{{ $mainSlide->cta_url }}" class="btn btn-vel-gold btn-lg px-4 py-3">
                {{ $mainSlide->cta_label ?? 'Shop Now' }}
                <i class="ms-2">→</i>
              </a>
            @endif
          </div>
        </div>
        <div class="col-lg-6">
          @if($mainSlide->image_url)
            <div class="crystal-card p-3">
              <img
                src="{{ $mainSlide->image_url }}"
                class="w-100 rounded-4"
                style="height: 400px; object-fit: cover;"
                alt="{{ $mainSlide->title ?? 'Hero' }}"
                loading="eager"
              >
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
          @endif

{{-- ====================== SLIDER SECTION ====================== --}}
@if(isset($visibility['slider']) && $visibility['slider'] && isset($sliderSlides) && $sliderSlides->count())
  <section class="py-5 reveal">
    <div class="container">
      <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          @foreach($sliderSlides as $index => $slide)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                    class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}"></button>
          @endforeach
        </div>
          <div class="carousel-inner">
          @foreach($sliderSlides as $index => $slide)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
              <img src="{{ $slide->image_url }}" class="carousel-img" alt="{{ $slide->title ?? 'Slide' }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
              <div class="carousel-overlay"></div>
              @if(!empty($slide->title) || !empty($slide->subtitle))
                <div class="carousel-caption">
                  @if(!empty($slide->title))
                    <h3 class="fw-bold">{{ $slide->title }}</h3>
                  @endif
                  @if(!empty($slide->subtitle))
                    <p class="mb-3">{{ $slide->subtitle }}</p>
                @endif
                  @if(!empty($slide->cta_url))
                    <a href="{{ $slide->cta_url }}" class="btn btn-vel-gold">
                      {{ $slide->cta_label ?? 'Learn More' }}
                      </a>
                    @endif
                </div>
              @endif
              </div>
            @endforeach
          </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
      </div>
        </div>
      </section>
    @endif

{{-- ====================== STATS SECTION ====================== --}}
<section class="stats-section reveal">
  <div class="container">
    <div class="row">
      <div class="col-md-3 col-6">
        <div class="stat-item">
          <div class="stat-number">{{ $specialProducts->count() }}+</div>
          <div class="stat-label">Featured Products</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stat-item">
          <div class="stat-number">{{ $categories->count() }}+</div>
          <div class="stat-label">Categories</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stat-item">
          <div class="stat-number">{{ $offers->count() }}+</div>
          <div class="stat-label">Active Offers</div>
        </div>
      </div>
      <div class="col-md-3 col-6">
        <div class="stat-item">
          <div class="stat-number">24/7</div>
          <div class="stat-label">Support</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====================== FEATURED PRODUCTS ====================== --}}
@if(isset($visibility['special']) && $visibility['special'] && isset($specialProducts) && $specialProducts->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="row g-4">
          @foreach($specialProducts as $p)
            <div class="col-6 col-md-3">
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
                    <span class="rating-text">(4.2)</span>
                  </div>

                  <!-- Product Tags -->
                  <div class="product-tags">
                    <span class="product-tag">Premium</span>
                    <span class="product-tag">New</span>
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
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

  {{-- ====================== CATEGORIES ====================== --}}
@if(isset($visibility['categories']) && $visibility['categories'] && isset($categories) && $categories->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="row g-4">
          @foreach($categories as $cat)
            <div class="col-6 col-md-3">
              <a href="{{ url('/category/' . $cat->slug) }}" class="category-card d-block text-decoration-none h-100">
                @if($cat->image_url)
                  <img src="{{ $cat->image_url }}" class="w-100" style="height: 200px; object-fit: cover;" alt="{{ $cat->name }}" loading="lazy">
                @else
                  <div class="w-100 d-flex align-items-center justify-content-center" style="height: 200px; background: var(--surface);">
                    <span class="text-muted">{{ $cat->name }}</span>
                  </div>
                @endif
                <div class="card-body text-center">
                  <h5 class="fw-semibold mb-0">{{ $cat->name }}</h5>
                </div>
              </a>
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

{{-- ====================== OFFERS ====================== --}}
@if(isset($visibility['offers']) && $visibility['offers'] && isset($offers) && $offers->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">Special Offers</h2>
        <div class="row g-4">
          @foreach($offers as $offer)
            <div class="col-12 col-md-4">
              <div class="offer-card h-100 p-4">
                @if($offer->banner_url)
                  <img src="{{ $offer->banner_url }}" class="w-100 rounded-3 mb-3" style="height: 200px; object-fit: cover;" alt="{{ $offer->title ?? 'Offer' }}" loading="lazy">
                @endif
                <div class="text-center">
                  @if(!empty($offer->title))
                    <h4 class="fw-semibold mb-2">{{ $offer->title }}</h4>
                  @endif
                  @if(!empty($offer->description))
                    <p class="text-muted mb-3">{{ $offer->description }}</p>
                  @endif
                  @if(!empty($offer->cta_url))
                    <a href="{{ $offer->cta_url }}" class="btn btn-vel-gold">
                      Shop Now
                    </a>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

{{-- ====================== NEW ARRIVALS ====================== --}}
@if(isset($visibility['latest']) && $visibility['latest'] && isset($latestProducts) && $latestProducts->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">New Arrivals</h2>
        <div class="row g-4">
          @foreach($latestProducts as $p)
            <div class="col-6 col-md-3">
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
                    <span class="rating-text">(4.5)</span>
                  </div>

                  <!-- Product Tags -->
                  <div class="product-tags">
                    <span class="product-tag">New</span>
                    <span class="product-tag">Trending</span>
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
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

  {{-- ====================== EXTERNAL BRANDS ====================== --}}
@if(isset($visibility['external']) && $visibility['external'] && isset($externalBrandProducts) && $externalBrandProducts->count())
  <section class="py-5 reveal">
      <div class="container">
        <h2 class="section-title">Premium Brands</h2>
        <div class="row g-4">
          @foreach($externalBrandProducts as $p)
            <div class="col-6 col-md-3">
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
                    <span class="rating-text">(4.8)</span>
                  </div>

                  <!-- Product Tags -->
                  <div class="product-tags">
                    <span class="product-tag">Premium</span>
                    <span class="product-tag">Brand</span>
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
            </div>
          @endforeach
        </div>
        </div>
      </section>
    @endif

{{-- ====================== CTA SECTION ====================== --}}
<section class="cta-section reveal">
  <div class="container position-relative">
    <h2 class="section-title text-white">Ready to Shop?</h2>
    <p class="lead text-white mb-4">Discover amazing products at unbeatable prices</p>
    <a href="{{ route('products.index') }}" class="btn btn-vel-gold btn-lg px-5 py-3">
      Browse All Products
      <i class="ms-2">→</i>
    </a>
</div>
</section>

@endsection

@push('scripts')
<script>
  // Enhanced reveal animations
  (function(){
    if (window.__velRevealBound) return;
    window.__velRevealBound = true;

    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Observe all reveal elements
    document.querySelectorAll('.reveal').forEach(el => {
      observer.observe(el);
    });

    // Add staggered animation to product cards
    document.querySelectorAll('.product-card').forEach((card, index) => {
      card.style.animationDelay = `${index * 0.1}s`;
    });
  })();

  // Parallax effect for hero section
  (function(){
    const hero = document.querySelector('.hero-section');
    if (!hero) return;

    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      const rate = scrolled * -0.5;
      hero.style.transform = `translateY(${rate}px)`;
    });
  })();
</script>
