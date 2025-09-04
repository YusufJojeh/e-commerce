@extends('layouts.app')

@section('title', 'Contact Us - ' . ($siteName ?? 'MyStore'))

@push('styles')
<style>
  .contact-header {
    background: linear-gradient(135deg, rgba(240,194,75,0.1) 0%, rgba(120,119,198,0.1) 100%);
    border-radius: 24px;
    padding: 4rem 2rem;
    margin-bottom: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .contact-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
  }

  .contact-title {
    font-size: 3rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--text) 0%, var(--gold) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
  }

  .contact-subtitle {
    color: var(--muted);
    font-size: 1.2rem;
    max-width: 600px;
    margin: 0 auto;
  }

  .contact-form {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 2rem;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text);
    display: block;
  }

  .form-control {
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    width: 100%;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(240,194,75,0.2);
    background: var(--surface);
    color: var(--text);
  }

  .form-control::placeholder {
    color: var(--muted);
  }

  textarea.form-control {
    min-height: 120px;
    resize: vertical;
  }

  .contact-info {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 2rem;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    height: 100%;
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

  /* Dark mode enhancements */
  html[data-theme="dark"] .contact-header {
    background: linear-gradient(135deg, rgba(240,194,75,0.15) 0%, rgba(120,119,198,0.15) 100%);
  }

  html[data-theme="dark"] .contact-form,
  html[data-theme="dark"] .contact-info {
    background: rgba(26,26,26,0.8);
    border-color: rgba(255,255,255,0.1);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
  }

  html[data-theme="dark"] .form-control {
    background: rgba(26,26,26,0.9);
    border-color: rgba(255,255,255,0.1);
    color: #f0f0f0;
  }

  html[data-theme="dark"] .form-control:focus {
    border-color: rgba(240,194,75,0.5);
    box-shadow: 0 0 0 3px rgba(240,194,75,0.3);
  }

  html[data-theme="dark"] .info-item {
    background: rgba(26,26,26,0.8);
    border-color: rgba(255,255,255,0.1);
  }

  html[data-theme="dark"] .social-link {
    background: rgba(26,26,26,0.8);
    border-color: rgba(255,255,255,0.1);
    color: #f0f0f0;
  }

  html[data-theme="dark"] .map-container {
    background: rgba(26,26,26,0.8);
    border-color: rgba(255,255,255,0.1);
  }

  html[data-theme="dark"] .map-placeholder {
    background: rgba(26,26,26,0.8);
    border-color: rgba(255,255,255,0.1);
  }
</style>
@endpush

@section('content')

{{-- Contact Header --}}
<section class="contact-header reveal">
  <div class="container position-relative">
    <h1 class="contact-title">Get in Touch</h1>
    <p class="contact-subtitle">
      Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
    </p>
  </div>
</section>

{{-- Contact Form and Info --}}
<section class="py-5 reveal">
  <div class="container">
    <div class="row g-4">
      {{-- Contact Form --}}
      <div class="col-lg-8">
        <div class="contact-form">
          <h3 class="mb-4">Send us a Message</h3>

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
                         placeholder="Your full name"
                         required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="email" class="form-label">Email Address *</label>
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
              <label for="subject" class="form-label">Subject *</label>
              <input type="text"
                     class="form-control"
                     id="subject"
                     name="subject"
                     placeholder="What is this about?"
                     required>
            </div>

            <div class="form-group">
              <label for="message" class="form-label">Message *</label>
              <textarea class="form-control"
                        id="message"
                        name="message"
                        placeholder="Tell us more about your inquiry..."
                        required></textarea>
            </div>

            <button type="submit" class="btn btn-vel-gold btn-lg px-4 py-2">
              Send Message
              <i class="ms-2">→</i>
            </button>
          </form>
        </div>
      </div>

      {{-- Contact Information --}}
      <div class="col-lg-4">
        <div class="contact-info">
          <h3 class="mb-4">Contact Information</h3>

          <div class="info-item">
            <div class="info-icon">📍</div>
            <div class="info-content">
              <h5>Address</h5>
              <p>123 Commerce Street<br>Business District<br>City, State 12345</p>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon">📧</div>
            <div class="info-content">
              <h5>Email</h5>
              <p>info@{{ str_replace(['http://', 'https://', 'www.'], '', config('app.url', 'mystore.com')) }}</p>
            </div>
          </div>

          <div class="info-item">
            <div class="info-icon">📞</div>
            <div class="info-content">
              <h5>Phone</h5>
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
            <a href="#" class="social-link" title="Facebook">📘</a>
            <a href="#" class="social-link" title="Twitter">🐦</a>
            <a href="#" class="social-link" title="Instagram">📷</a>
            <a href="#" class="social-link" title="LinkedIn">💼</a>
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
      alert('Please fill in all required fields.');
      return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert('Please enter a valid email address.');
      return;
    }

    // Show success message (replace with actual form submission)
    alert('Thank you for your message! We\'ll get back to you soon.\n\n' +
          'Name: ' + name + '\n' +
          'Email: ' + email + '\n' +
          'Subject: ' + subject + '\n' +
          'Message: ' + message);

    // Reset form
    this.reset();
  });
</script>
@endpush
