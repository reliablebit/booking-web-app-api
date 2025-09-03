<template>
  <section class="cta-section">
    <!-- Animated Background -->
    <div class="cta-background">
      <div class="gradient-overlay"></div>
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
      </div>
    </div>

    <div class="cta-container">
      <div class="cta-content">
        <!-- Badge -->
        <!-- <div class="cta-badge">
          <span class="badge-text">
            Join 10,000+ Happy Users
          </span>
        </div> -->

        <!-- Main Heading -->
        <h2 class="cta-heading">
          Ready to Transform Your
          <span class="gradient-text">Booking Experience</span>?
        </h2>

        <!-- Subtitle -->
        <p class="cta-subtitle">
          Start your journey with BookingApp today and discover how easy managing bookings can be.
          Join thousands of businesses already streamlining their operations.
        </p>

        <!-- Features List -->
        <div class="cta-features">
          <div class="feature-item">
            <div class="feature-icon">✓</div>
            <span>Free 14-day trial</span>
          </div>
          <div class="feature-item">
            <div class="feature-icon">✓</div>
            <span>No credit card required</span>
          </div>
          <div class="feature-item">
            <div class="feature-icon">✓</div>
            <span>Cancel anytime</span>
          </div>
        </div>

        <!-- CTA Buttons -->
        <div class="cta-buttons">
          <router-link to="/register-choice" class="btn-primary">
            <span class="btn-text">Start Free Trial</span>
            <div class="btn-shine"></div>
            <i class="btn-arrow">→</i>
          </router-link>

          <button class="btn-secondary" @click="openDemo">
            <i class="play-icon">▶</i>
            Watch Demo
          </button>
        </div>

        <!-- Trust Indicators -->
        <div class="trust-indicators">
          <div class="trust-item">
            <div class="trust-number">4.9</div>
            <!-- <div class="trust-stars">⭐⭐⭐⭐⭐</div> -->
            <div class="trust-text">User Rating</div>
          </div>
          <div class="trust-item">
            <div class="trust-number">99.9%</div>
            <div class="trust-text">Uptime</div>
          </div>
          <div class="trust-item">
            <div class="trust-number">24/7</div>
            <div class="trust-text">Support</div>
          </div>
        </div>

        <!-- Security Badge -->
        <div class="security-badge">
          <!-- <i class="security-icon">🔒</i> -->
          <span>Enterprise-grade security & GDPR compliant</span>
        </div>
      </div>
    </div>

    <!-- Bottom Wave -->
    <div class="bottom-wave">
      <svg viewBox="0 0 1440 120" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0,64L48,69.3C96,75,192,85,288,85.3C384,85,480,75,576,69.3C672,64,768,64,864,69.3C960,75,1056,85,1152,85.3C1248,85,1344,75,1392,69.3L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"
          fill="white"
        />
      </svg>
    </div>
  </section>
</template>

<script>
export default {
  name: 'CallToAction',
  methods: {
    openDemo() {
      // You can implement demo modal or redirect to demo page
      alert('Demo feature coming soon!')
    },
  },
}
</script>

<style scoped>
.cta-section {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: white;
}

/* Animated Background */
.cta-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
}

.gradient-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.05) 0%,
    rgba(37, 99, 235, 0.03) 50%,
    rgba(29, 78, 216, 0.02) 100%
  );
}

.floating-shapes {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.shape {
  position: absolute;
  border-radius: 50%;
  animation: float 6s ease-in-out infinite;
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  opacity: 0.1;
}

.shape-1 {
  width: 80px;
  height: 80px;
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.shape-2 {
  width: 120px;
  height: 120px;
  top: 60%;
  right: 15%;
  animation-delay: 2s;
}

.shape-3 {
  width: 60px;
  height: 60px;
  top: 10%;
  right: 25%;
  animation-delay: 4s;
}

.shape-4 {
  width: 100px;
  height: 100px;
  bottom: 30%;
  left: 20%;
  animation-delay: 1s;
}

.shape-5 {
  width: 140px;
  height: 140px;
  top: 40%;
  left: 60%;
  animation-delay: 3s;
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  33% {
    transform: translateY(-30px) rotate(120deg);
  }
  66% {
    transform: translateY(30px) rotate(240deg);
  }
}

/* Main Container */
.cta-container {
  position: relative;
  z-index: 2;
  max-width: 900px;
  margin: 0 auto;
  padding: 4rem 2rem;
  text-align: center;
}

.cta-content {
  color: #1f2937;
}

/* Badge */
.cta-badge {
  display: inline-block;
  margin-bottom: 2rem;
  animation: slideInDown 1s ease-out;
}

.badge-text {
  background: rgba(59, 130, 246, 0.1);
  border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 50px;
  padding: 0.75rem 1.5rem;
  font-size: 0.9rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #3b82f6;
}

.badge-icon {
  font-size: 1.2rem;
  animation: pulse 2s infinite;
}

/* Main Heading */
.cta-heading {
  font-size: 3.5rem;
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 1.5rem;
  animation: slideInUp 1s ease-out 0.2s both;
  color: #111827;
}

.gradient-text {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  display: inline-block;
  animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
  0%,
  100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

/* Subtitle */
.cta-subtitle {
  font-size: 1.25rem;
  line-height: 1.6;
  margin-bottom: 2.5rem;
  color: #6b7280;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  animation: slideInUp 1s ease-out 0.4s both;
}

/* Features */
.cta-features {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin-bottom: 3rem;
  flex-wrap: wrap;
  animation: slideInUp 1s ease-out 0.6s both;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 500;
  color: #374151;
}

.feature-icon {
  width: 24px;
  height: 24px;
  background: rgba(59, 130, 246, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  color: #3b82f6;
  font-weight: bold;
}

/* CTA Buttons */
.cta-buttons {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  margin-bottom: 3rem;
  flex-wrap: wrap;
  animation: slideInUp 1s ease-out 0.8s both;
}

.btn-primary {
  position: relative;
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: white;
  padding: 1rem 2.5rem;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
  transition: all 0.3s ease;
  overflow: hidden;
  border: none;
  cursor: pointer;
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 40px rgba(59, 130, 246, 0.4);
}

.btn-shine {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.6s;
}

.btn-primary:hover .btn-shine {
  left: 100%;
}

.btn-arrow {
  font-size: 1.2rem;
  transition: transform 0.3s ease;
}

.btn-primary:hover .btn-arrow {
  transform: translateX(5px);
}

.btn-secondary {
  background: white;
  border: 2px solid #3b82f6;
  color: #3b82f6;
  padding: 1rem 2rem;
  border-radius: 50px;
  font-weight: 600;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 16px rgba(59, 130, 246, 0.1);
}

.btn-secondary:hover {
  background: #3b82f6;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(59, 130, 246, 0.2);
}

.play-icon {
  font-size: 0.9rem;
}

/* Trust Indicators */
.trust-indicators {
  display: flex;
  justify-content: center;
  gap: 3rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  animation: slideInUp 1s ease-out 1s both;
}

.trust-item {
  text-align: center;
}

.trust-number {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 0.25rem;
  color: #3b82f6;
}

.trust-text {
  font-size: 0.9rem;
  color: #6b7280;
  font-weight: 500;
}

/* Security Badge */
.security-badge {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  color: #6b7280;
  animation: slideInUp 1s ease-out 1.2s both;
}

.security-icon {
  font-size: 1rem;
}

/* Bottom Wave */
.bottom-wave {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 120px;
  z-index: 2;
}

.bottom-wave svg {
  width: 100%;
  height: 100%;
  display: block;
}

/* Animations */
@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translateY(-50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes pulse {
  0%,
  100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
}

/* Responsive Design */
@media (max-width: 768px) {
  .cta-container {
    padding: 3rem 1.5rem;
  }

  .cta-heading {
    font-size: 2.5rem;
  }

  .cta-subtitle {
    font-size: 1.1rem;
  }

  .cta-features {
    flex-direction: column;
    gap: 1rem;
  }

  .cta-buttons {
    flex-direction: column;
    align-items: center;
  }

  .trust-indicators {
    gap: 2rem;
  }

  .trust-number {
    font-size: 1.5rem;
  }

  .shape {
    opacity: 0.05;
  }
}

@media (max-width: 480px) {
  .cta-heading {
    font-size: 2rem;
  }

  .cta-subtitle {
    font-size: 1rem;
  }

  .btn-primary,
  .btn-secondary {
    padding: 0.875rem 2rem;
    font-size: 1rem;
  }

  .trust-indicators {
    gap: 1.5rem;
  }
}
</style>
