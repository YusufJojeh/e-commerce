@extends('layouts.app')

@section('title', 'Contact Us - ' . ($siteName ?? 'MyStore'))

@push('styles')
@include('partials.unified-styles')
<style>
  /* Contact Page Specific Styles */

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text);
    display: block;
  }

  textarea.form-control {
    min-height: 120px;
    resize: vertical;
  }

  .info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--surface);
    border-radius: 16px;
    border: 1px solid var(--border);
  }

  .info-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--gold), #ffd700);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #111216;
    flex-shrink: 0;
  }

  .info-content h5 {
    margin: 0 0 0.25rem 0;
    color: var(--text);
    font-weight: 600;
  }

  .info-content p {
    margin: 0;
    color: var(--muted);
    font-size: 0.9rem;
  }

  .social-links {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
  }

  .social-link {
    width: 48px;
    height: 48px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.25rem;
  }

  .social-link:hover {
    background: var(--gold);
    color: #111216;
    border-color: var(--gold);
    transform: translateY(-2px);
    text-decoration: none;
  }

  .map-container {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 2rem;
    margin-top: 3rem;
    text-align: center;
  }

  .map-placeholder {
    background: var(--glass);
    border: 2px dashed var(--border);
    border-radius: 16px;
    padding: 3rem 2rem;
    color: var(--muted);
  }

  .map-placeholder-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
  }
</style>
@endpush

@section('content')

{{-- Contact Header --}}
<div class="container py-4">
  <div class="page-header text-center mb-5">
    <h1 class="page-title">{{ __('common.messages.get_in_touch') }}</h1>
    <p class="page-subtitle text-muted">
      {{ __('common.messages.have_questions') }}
    </p>
  </div>

{{-- Contact Form and Info --}}
<section class="py-5 reveal">
  <div class="container">
    <div class="row g-4">
      {{-- Contact Form --}}
      <div class="col-lg-8">
        <div class="content-card">
          <h3 class="mb-4">{{ __('common.pages.send_message') }}</h3>

          <form action="#" method="POST" id="contactForm">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name" class="form-label">Full Name *</label>
                  <input type="text"
                         class="form-control"
                         id="name"
                         name="name"
                         placeholder="{{ __('common.messages.your_full_name') }}"
                         required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="email" class="form-label">{{ __('common.fields.email') }} *</label>
                  <input type="email"
                         class="form-control"
                         id="email"
                         name="email"
                         placeholder="your.email@example.com"
                         required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="subject" class="form-label">{{ __('common.fields.subject') }} *</label>
              <input type="text"
                     class="form-control"
                     id="subject"
                     name="subject"
                     placeholder="{{ __('common.messages.what_is_this_about') }}"
                     required>
            </div>

            <div class="form-group">
              <label for="message" class="form-label">{{ __('common.fields.message') }} *</label>
              <textarea class="form-control"
                        id="message"
                        name="message"
                        placeholder="{{ __('common.messages.tell_us_more') }}"
                        required></textarea>
            </div>

            <button type="submit" class="btn btn-enhanced btn-lg px-4 py-2">
              {{ __('common.actions.send_message') }}
              <i class="ms-2">→</i>
            </button>
          </form>
        </div>
      </div>

      {{-- Contact Information --}}
      <div class="col-lg-4">
        <div class="content-card">
          <h3 class="mb-4">{{ __('common.pages.contact_information') }}</h3>

          <div class="info-item">
            <div class="info-icon">📍</div>
            <div class="info-content">
              <h5>{{ __('common.fields.address') }}</h5>
              <p>123 Commerce Street<br>Business District<br>City, State 12345</p>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon">📧</div>
            <div class="info-content">
              <h5>{{ __('common.fields.email') }}</h5>
              <p>info@{{ str_replace(['http://', 'https://', 'www.'], '', config('app.url', 'mystore.com')) }}</p>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon">📞</div>
            <div class="info-content">
              <h5>{{ __('common.fields.phone') }}</h5>
              <p>+1 (555) 123-4567</p>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon">🕒</div>
            <div class="info-content">
              <h5>Business Hours</h5>
              <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
            </div>
          </div>

          <div class="social-links">
            <a href="#" class="social-link" title="{{ __('common.messages.facebook') }}">📘</a>
            <a href="#" class="social-link" title="{{ __('common.messages.twitter') }}">🐦</a>
            <a href="#" class="social-link" title="{{ __('common.messages.instagram') }}">📷</a>
            <a href="#" class="social-link" title="{{ __('common.messages.linkedin') }}">💼</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Map Section --}}
<section class="py-5 reveal">
  <div class="container">
    <div class="map-container">
      <h3 class="mb-4">Find Us</h3>
      <div class="map-placeholder">
        <div class="map-placeholder-icon">🗺️</div>
        <h5>Interactive Map</h5>
        <p>Map integration coming soon. For now, you can find us at our address above.</p>
        <p class="small text-muted">
          <strong>Address:</strong> 123 Commerce Street, Business District, City, State 12345
        </p>
      </div>
    </div>
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
  })();
</script>
@endpush

@push('scripts')
<script>
  // Simple form handling (you can wire this to your backend)
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get form data
    const formData = new FormData(this);
    const name = formData.get('name');
    const email = formData.get('email');
    const subject = formData.get('subject');
    const message = formData.get('message');

    // Simple validation
    if (!name || !email || !subject || !message) {
      alert('{{ __('common.messages.please_fill_required') }}');
      return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('{{ __('common.messages.please_enter_valid_email') }}');
      return;
    }

    // Show success message (replace with actual form submission)
    alert('{{ __('common.messages.thank_you_message') }}\n\n' +
          '{{ __('common.messages.name') }}: ' + name + '\n' +
          '{{ __('common.messages.email') }}: ' + email + '\n' +
          '{{ __('common.messages.subject') }}: ' + subject + '\n' +
          '{{ __('common.fields.message') }}: ' + message);

    // Reset form
    this.reset();
  });
</script>
@endpush
