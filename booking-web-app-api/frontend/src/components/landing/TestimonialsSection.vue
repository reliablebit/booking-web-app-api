<template>
  <section id="testimonials" class="testimonials-section q-pa-xl">
    <!-- Background Elements -->
    <div class="background-pattern"></div>
    <div class="floating-elements">
      <div class="floating-circle circle-1"></div>
      <div class="floating-circle circle-2"></div>
      <div class="floating-circle circle-3"></div>
    </div>

    <div class="container">
      <!-- Section Header -->
      <div class="text-center section-header q-mb-xl">
        <div class="text-overline text-primary testimonial-badge">
          <q-icon name="person" size="sm" class="q-mr-xs" />
          TESTIMONIALS
        </div>
        <div class="text-h2 text-weight-bold text-dark q-mt-md q-mb-lg">
          Trusted by Thousands of Businesses
        </div>
        <p class="text-h6 section-subtitle">
          See what our customers are saying about their experience with BookingMaster
        </p>
      </div>

      <!-- Testimonials Grid -->
      <div class="testimonials-grid q-mb-xl">
        <div class="row q-col-gutter-xl">
          <div
            class="col-md-4 col-sm-12"
            v-for="(testimonial, index) in testimonials"
            :key="'grid-' + index"
            data-aos="fade-up"
            :data-aos-delay="index * 150"
          >
            <q-card flat class="testimonial-card">
              <div class="card-shine"></div>
              <q-card-section class="testimonial-content">
                <div class="quote-mark">
                  <q-icon name="format_quote" class="quote-icon" />
                </div>

                <div class="rating-section q-mb-md">
                  <div class="stars-wrapper">
                    <q-rating
                      v-model="testimonial.rating"
                      size="1.4em"
                      color="orange-6"
                      icon="star"
                      icon-selected="star"
                      readonly
                      class="rating-stars"
                    />
                  </div>
                </div>

                <p class="testimonial-text text-body1">{{ testimonial.text }}</p>

                <div class="author-section q-mt-lg">
                  <div class="row items-center no-wrap">
                    <div class="avatar-wrapper q-mr-md">
                      <q-avatar size="56px" class="author-avatar">
                        <img :src="testimonial.avatar" />
                      </q-avatar>
                      <div class="avatar-ring"></div>
                    </div>
                    <div class="author-info">
                      <div class="text-h6 text-weight-bold author-name">{{ testimonial.name }}</div>
                      <div class="text-caption author-position">
                        {{ testimonial.position }}
                      </div>
                      <div class="text-caption company-name">
                        {{ testimonial.company }}
                      </div>
                    </div>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
      </div>

      <!-- Enhanced Logo Cloud -->
      <div class="logo-cloud q-mt-xl q-pt-xl">
        <div class="text-center q-mb-xl">
          <div class="text-h5 text-weight-medium logo-cloud-title">Trusted by industry leaders</div>
        </div>
        <div class="logo-container">
          <div class="logo-track">
            <div
              class="logo-item"
              v-for="(logo, index) in [...logos, ...logos]"
              :key="'logo-' + index"
            >
              <q-img
                :src="logo.src"
                :alt="logo.alt"
                width="100px"
                height="40px"
                class="company-logo"
                fit="contain"
              />
            </div>
          </div>
        </div>
      </div>


    </div>
  </section>
</template>

<script>
import AOS from 'aos'
import 'aos/dist/aos.css'

export default {
  name: 'TestimonialsSection',
  data() {
    return {
      testimonials: [
        {
          name: 'Sarah Johnson',
          position: 'CEO',
          company: 'EventSpace Inc.',
          text: 'BookingMaster has completely transformed how we manage our venue bookings. The visual seat management feature alone has saved us over 10 hours per week in administrative work.',
          rating: 5,
          avatar: 'https://cdn.quasar.dev/img/avatar1.jpg',
        },
        {
          name: 'Michael Chen',
          position: 'Operations Director',
          company: 'Grand Theater',
          text: "The analytics dashboard provides insights we never had access to before. We've increased our occupancy rate by 22% and boosted revenue by 18% in just three months.",
          rating: 5,
          avatar: 'https://cdn.quasar.dev/img/avatar2.jpg',
        },
        {
          name: 'Jessica Williams',
          position: 'Business Owner',
          company: 'Cafe Luna',
          text: 'As a small business, the starter plan gives us everything we need at an affordable price. The customer support team is exceptional - always responsive and helpful.',
          rating: 4,
          avatar: 'https://cdn.quasar.dev/img/avatar4.jpg',
        },
      ],
      logos: [
        { src: 'https://cdn.quasar.dev/logo-v2/svg/logo.svg', alt: 'Company 1' },
        { src: 'https://cdn.quasar.dev/logo-v2/svg/logo.svg', alt: 'Company 2' },
        { src: 'https://cdn.quasar.dev/logo-v2/svg/logo.svg', alt: 'Company 3' },
        { src: 'https://cdn.quasar.dev/logo-v2/svg/logo.svg', alt: 'Company 4' },
        { src: 'https://cdn.quasar.dev/logo-v2/svg/logo.svg', alt: 'Company 5' },
      ],
    }
  },
  mounted() {
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 1000,
        once: true,
        offset: 100,
        easing: 'ease-out-cubic',
      })
    }
  },
}
</script>

<style scoped>
.testimonials-section {
  /* background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%); */
  position: relative;
  overflow: hidden;
  min-height: 100vh;
}

/* Background Elements */
.background-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image:
    radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 75% 75%, rgba(168, 85, 247, 0.1) 0%, transparent 50%);
  background-size:
    800px 800px,
    600px 600px;
  animation: backgroundFloat 20s ease-in-out infinite alternate;
}

.floating-elements {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
  z-index: 1;
}

.floating-circle {
  position: absolute;
  border-radius: 50%;
  background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(168, 85, 247, 0.1));
  animation: float 6s ease-in-out infinite;
}

.circle-1 {
  width: 200px;
  height: 200px;
  top: 10%;
  left: 10%;
  animation-delay: 0s;
}

.circle-2 {
  width: 150px;
  height: 150px;
  top: 60%;
  right: 15%;
  animation-delay: 2s;
}

.circle-3 {
  width: 100px;
  height: 100px;
  top: 30%;
  right: 30%;
  animation-delay: 4s;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

/* Section Header */
.section-header {
  position: relative;
  z-index: 3;
}

.testimonial-badge {
  display: inline-flex;
  align-items: center;
  background: white;
  border: 2px solid #e2e8f0;
  padding: 12px 24px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.875rem;
  letter-spacing: 1px;
  margin-bottom: 2px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.gradient-text {
  background: linear-gradient(135deg, #1e293b, #475569);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.section-subtitle {
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
  color: #64748b;
  font-weight: 400;
}

/* Testimonial Cards */
.testimonials-grid {
  position: relative;
  z-index: 2;
}

.testimonial-card {
  border-radius: 24px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow:
    0 20px 40px rgba(0, 0, 0, 0.08),
    0 4px 12px rgba(0, 0, 0, 0.05);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  height: 100%;
}

.testimonial-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow:
    0 30px 60px rgba(0, 0, 0, 0.15),
    0 8px 24px rgba(0, 0, 0, 0.1);
  border-color: rgba(59, 130, 246, 0.3);
}

.card-shine {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
  transition: left 0.8s ease;
  z-index: 1;
}

.testimonial-card:hover .card-shine {
  left: 100%;
}

.testimonial-content {
  position: relative;
  z-index: 2;
  padding: 32px;
}

.quote-mark {
  position: absolute;
  top: -10px;
  right: 20px;
  z-index: 0;
}

.quote-icon {
  font-size: 60px;
  color: rgba(59, 130, 246, 0.1);
  transform: rotate(180deg);
}

/* Rating Section */
.rating-section {
  position: relative;
  z-index: 3;
}

.stars-wrapper {
  display: inline-flex;
  padding: 8px 16px;
  background: rgba(255, 237, 213, 0.5);
  border-radius: 50px;
  border: 1px solid rgba(251, 191, 36, 0.2);
}

.rating-stars {
  filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.3));
}

/* Testimonial Text */
.testimonial-text {
  line-height: 1.7;
  color: #374151;
  font-size: 16px;
  position: relative;
  z-index: 3;
  min-height: 120px;
  font-weight: 400;
  margin-bottom: 0;
}

/* Author Section */
.author-section {
  position: relative;
  z-index: 3;
  border-top: 1px solid rgba(229, 231, 235, 0.5);
  padding-top: 24px;
}

.avatar-wrapper {
  position: relative;
}

.author-avatar {
  border: 3px solid rgba(255, 255, 255, 0.8);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.avatar-ring {
  position: absolute;
  top: -2px;
  left: -2px;
  right: -2px;
  bottom: -2px;
  border-radius: 50%;
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  z-index: -1;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.testimonial-card:hover .avatar-ring {
  opacity: 1;
}

.author-info {
  flex: 1;
  min-width: 0;
}

.author-name {
  color: #1f2937;
  margin-bottom: 2px;
  font-size: 16px;
}

.author-position {
  color: #6b7280;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 2px;
}

.company-name {
  color: #3b82f6;
  font-weight: 600;
}

/* Enhanced Stats Section */
.stats-section {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(20px);
  border-radius: 32px;
  padding: 60px 40px;
  margin: 0 auto;
  max-width: 1000px;
  position: relative;
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow:
    0 20px 40px rgba(0, 0, 0, 0.08),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  overflow: hidden;
}

.stats-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(168, 85, 247, 0.05) 100%);
  z-index: 0;
}

.stat-item {
  position: relative;
  z-index: 2;
  transition: transform 0.3s ease;
}

.stat-item:hover {
  transform: translateY(-5px);
}

.stat-icon-wrapper {
  width: 60px;
  height: 60px;
  margin: 0 auto;
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
}

.stat-icon {
  color: white;
  font-size: 24px;
}

.stat-number {
  font-size: 3.5rem;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 8px;
  background: linear-gradient(135deg, #1e293b, #3b82f6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.stat-label {
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #64748b;
  font-size: 14px;
}

/* Enhanced Logo Cloud */
.logo-cloud {
  position: relative;
  padding-top: 60px;
}

.logo-cloud::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 120px;
  height: 4px;
  background: linear-gradient(90deg, #3b82f6, #8b5cf6);
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.logo-cloud-title {
  color: #374151;
  font-weight: 600;
  margin-bottom: 40px;
}

.logo-container {
  width: 100%;
  overflow: hidden;
  mask: linear-gradient(90deg, transparent 0%, black 15%, black 85%, transparent 100%);
  -webkit-mask: linear-gradient(90deg, transparent 0%, black 15%, black 85%, transparent 100%);
}

.logo-track {
  display: flex;
  animation: logoScroll 30s linear infinite;
  width: fit-content;
}

.logo-item {
  flex-shrink: 0;
  margin: 0 40px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.company-logo {
  filter: grayscale(100%) contrast(0.5);
  opacity: 0.6;
  transition: all 0.4s ease;
}

.company-logo:hover {
  filter: grayscale(0%) contrast(1);
  opacity: 1;
  transform: scale(1.1);
}

/* Animations */
@keyframes backgroundFloat {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(5deg);
  }
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg) scale(1);
  }
  33% {
    transform: translateY(-10px) rotate(5deg) scale(1.05);
  }
  66% {
    transform: translateY(-5px) rotate(-3deg) scale(0.95);
  }
}

@keyframes logoScroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

/* Responsive Design */
@media (max-width: 1023px) {
  .testimonials-section {
    padding: 60px 24px;
  }

  .text-h2 {
    font-size: 2.5rem;
  }

  .testimonial-content {
    padding: 24px;
  }

  .stats-section {
    padding: 40px 20px;
  }

  .stat-number {
    font-size: 2.5rem;
  }

  .floating-circle {
    display: none;
  }
}

@media (max-width: 899px) {
  .testimonials-grid .col-md-4 {
    margin-bottom: 30px;
  }

  .testimonial-text {
    min-height: auto;
  }
}

@media (max-width: 599px) {
  .testimonials-section {
    padding: 40px 16px;
  }

  .text-h2 {
    font-size: 2rem;
  }

  .testimonial-content {
    padding: 20px;
  }

  .stats-section {
    padding: 30px 16px;
    border-radius: 20px;
  }

  .stat-number {
    font-size: 2rem;
  }

  .logo-item {
    margin: 0 20px;
  }

  .stat-icon-wrapper {
    width: 50px;
    height: 50px;
  }

  .stat-icon {
    font-size: 20px;
  }
}

/* Enhanced AOS animations */
[data-aos] {
  opacity: 0;
  transition-property: transform, opacity;
}

[data-aos].aos-animate {
  opacity: 1;
}

[data-aos='fade-up'] {
  transform: translateY(50px);
}

[data-aos='fade-up'].aos-animate {
  transform: translateY(0);
}

[data-aos='zoom-in'] {
  transform: scale(0.8);
}

[data-aos='zoom-in'].aos-animate {
  transform: scale(1);
}
</style>
