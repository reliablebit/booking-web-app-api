<template>
  <section id="features" class="features-section q-pa-xl">
    <div class="container">
      <!-- Section Header -->
      <div class="text-center section-header q-mb-xl">
        <div class="feature-badge text-primary">
          <q-icon name="star" size="sm" class="q-mr-xs" />
          CORE FEATURES
        </div>
        <div class="section-title text-weight-bold text-dark q-mt-md q-mb-lg">
          Powerful Booking Management Tools
        </div>
        <p class="section-subtitle text-grey-7">
          Everything you need to streamline reservations, manage availability, and grow your
          business with enterprise-grade solutions
        </p>
      </div>

      <!-- Features Grid -->
      <div class="features-grid">
        <div class="row q-col-gutter-xl">
          <div
            class="col-md-4 col-sm-6 col-xs-12"
            v-for="(feature, index) in features"
            :key="index"
            data-aos="fade-up"
            :data-aos-delay="index * 100"
          >
            <q-card class="feature-card text-center">
              <div class="card-overlay"></div>

              <div class="feature-icon-wrapper">
                <div class="icon-background"></div>
                <q-icon
                  :name="feature.icon"
                  size="xl"
                  :color="feature.color"
                  class="feature-icon"
                />
              </div>

              <q-card-section class="feature-content q-pt-none">
                <div class="feature-title text-weight-bold text-dark q-mt-md q-mb-sm">
                  {{ feature.title }}
                </div>
                <p class="feature-description text-grey-7">{{ feature.description }}</p>
              </q-card-section>

              <q-card-actions class="justify-center q-pb-lg">
                <q-btn
                  flat
                  :color="feature.color"
                  label="Learn More"
                  icon-right="arrow_forward"
                  size="sm"
                  class="feature-btn"
                  no-caps
                />
              </q-card-actions>
            </q-card>
          </div>
        </div>
      </div>

      <!-- Stats Section -->
      <div class="stats-section q-mt-xl q-pt-xl">
        <div class="stats-container">
          <div class="row justify-center items-center text-center">
            <div
              class="col-md-3 col-sm-6 col-xs-12 q-pa-md"
              v-for="(stat, index) in stats"
              :key="'stat-' + index"
              data-aos="zoom-in"
              :data-aos-delay="index * 100"
            >
              <div class="stat-card">
                <div class="stat-icon">
                  <q-icon :name="stat.icon" size="md" color="primary" />
                </div>
                <div class="stat-number text-weight-bold text-primary">{{ stat.value }}</div>
                <div class="stat-label text-grey-7 q-mt-sm">{{ stat.label }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div class="cta-section text-center q-mt-xl">
        <div class="cta-content" data-aos="fade-up">
          <h3 class="cta-title text-dark">Ready to Transform Your Business?</h3>
          <p class="cta-subtitle text-grey-7">Join thousands of satisfied customers worldwide</p>
          <div class="cta-buttons q-mt-lg">
            <q-btn
              unelevated
              color="primary"
              label="Start Free Trial"
              @click="scrollToSection('pricing')"
              size="lg"
              class="cta-btn-primary q-mr-md"
              icon="rocket_launch"
              no-caps
            />

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
  name: 'FeaturesSection',
  data() {
    return {
      features: [
        {
          icon: 'event_seat',
          color: 'primary',
          title: 'Smart Seat Management',
          description:
            'Interactive seat maps with drag-and-drop editing, real-time availability tracking, and optimized seating arrangements.',
        },
        {
          icon: 'payments',
          color: 'secondary',
          title: 'Secure Payment Processing',
          description:
            'Multiple payment gateways, PCI-compliant encryption, fraud detection, and automated refund processing.',
        },
        {
          icon: 'analytics',
          color: 'accent',
          title: 'Advanced Analytics Suite',
          description:
            'Comprehensive dashboards with real-time insights, performance metrics, and predictive analytics.',
        },
        {
          icon: 'calendar_today',
          color: 'positive',
          title: 'Unified Booking Calendar',
          description:
            'Visual calendar interface with drag-and-drop scheduling, conflict detection, and multi-view options.',
        },
        {
          icon: 'notifications',
          color: 'orange',
          title: 'Automated Notifications',
          description:
            'Customizable alerts, SMS/email reminders, and intelligent follow-up system for better engagement.',
        },
        {
          icon: 'admin_panel_settings',
          color: 'purple',
          title: 'Role-Based Access Control',
          description:
            'Granular permissions, multi-level administration, and audit trails for complete security.',
        },
      ],
      stats: [
        { value: '99.9%', label: 'System Uptime', icon: 'verified' },
        { value: '5,000+', label: 'Active Merchants', icon: 'business' },
        { value: '1M+', label: 'Monthly Bookings', icon: 'trending_up' },
        { value: '24/7', label: 'Support Available', icon: 'support_agent' },
      ],
    }
  },
  methods: {
    scrollToSection(sectionId) {
      const element = document.getElementById(sectionId)
      if (element) {
        element.scrollIntoView({ behavior: 'smooth' })
      }
    },
  },
  mounted() {
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-out-cubic',
      })
    }
  },
}
</script>

<style scoped>
/* Section Base */
.features-section {
  background-color: white;
  /* background: linear-gradient(135deg, #f9f9f9 0%, #f5f6f7 100%); */
  position: relative;
  overflow: hidden;
  padding: 100px 0;
}

.features-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  /* background: linear-gradient(90deg, transparent, #e2e8f0, transparent); */
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

/* Header Styling */
.feature-badge {
  display: inline-flex;
  align-items: center;
  background: white;
  border: 2px solid #e2e8f0;
  padding: 12px 24px;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.875rem;
  letter-spacing: 1px;
  margin-bottom: 32px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.section-title {
  font-size: clamp(2.5rem, 5vw, 3.5rem);
  font-weight: 800;
  line-height: 1.2;
  margin: 0 0 24px 0;
  color: #1e293b;
}

.section-subtitle {
  font-size: 1.25rem;
  line-height: 1.7;
  color: #64748b;
  max-width: 700px;
  margin: 0 auto;
  font-weight: 400;
}

/* Feature Cards */
.features-grid {
  margin: 80px 0;
}

.feature-card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  height: 100%;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
}

.card-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--q-primary), var(--q-secondary));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.feature-card:hover .card-overlay {
  opacity: 1;
}

.feature-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
  border-color: #cbd5e1;
}

.feature-icon-wrapper {
  padding: 40px 0 20px;
  position: relative;
}

.icon-background {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 90px;
  height: 90px;
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(168, 85, 247, 0.08));
  border-radius: 22px;
  transition: all 0.3s ease;
}

.feature-card:hover .icon-background {
  transform: translate(-50%, -50%) scale(1.1) rotate(3deg);
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(168, 85, 247, 0.12));
}

.feature-icon {
  font-size: 3rem;
  position: relative;
  z-index: 1;
  transition: all 0.3s ease;
}

.feature-card:hover .feature-icon {
  transform: scale(1.05);
}

.feature-content {
  padding: 0 32px;
}

.feature-title {
  font-size: 1.375rem;
  font-weight: 700;
  color: #1e293b;
  line-height: 1.3;
}

.feature-description {
  font-size: 0.95rem;
  line-height: 1.6;
  color: #64748b;
  margin: 0;
  font-weight: 400;
  min-height: 72px;
}

.feature-btn {
  border-radius: 12px;
  font-weight: 600;
  padding: 10px 20px;
  transition: all 0.3s ease;
  background: rgba(59, 130, 246, 0.05);
  border: 1px solid transparent;
}

.feature-btn:hover {
  background: rgba(59, 130, 246, 0.1);
  transform: translateX(4px);
}

/* Enhanced Stats */
.stats-section {
  /* margin-top: 100px; */
  position: relative;
}

.stats-container {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 24px;
  /* padding: 60px 20px; */
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
  position: relative;
}

.stats-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, var(--q-primary), var(--q-secondary));
  border-radius: 2px;
}

.stat-card {
  padding: 20px;
  transition: all 0.3s ease;
  border-radius: 16px;
}

.stat-card:hover {
  background: rgba(59, 130, 246, 0.03);
  transform: translateY(-4px);
}

.stat-icon {
  margin-bottom: 16px;
  opacity: 0.8;
}

.stat-number {
  font-size: clamp(2.5rem, 4vw, 3.5rem);
  line-height: 1;
  margin-bottom: 8px;
}

.stat-label {
  font-size: 0.9rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Call to Action */
.cta-content {
  background: linear-gradient(135deg, #f8fafc, white);
  border: 2px solid #e2e8f0;
  border-radius: 24px;
  padding: 60px 40px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
}

.cta-content::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
}

.cta-title {
  font-size: clamp(1.8rem, 3vw, 2.25rem);
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 16px 0;
}

.cta-subtitle {
  font-size: 1.1rem;
  color: #64748b;
  margin: 0 0 32px 0;
}

.cta-btn-primary {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  border-radius: 12px;
  padding: 12px 26px;
  font-weight: 600;
  box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
  transition: all 0.3s ease;
}

.cta-btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(59, 130, 246, 0.35);
}

.cta-btn-secondary {
  border-radius: 12px;
  padding: 14px 28px;
  font-weight: 600;
  border: 2px solid #3b82f6;
  transition: all 0.3s ease;
}

.cta-btn-secondary:hover {
  background: rgba(59, 130, 246, 0.05);
  transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 1023px) {
  .features-section {
    padding: 80px 24px;
  }

  .feature-content {
    padding: 0 24px;
  }

  .cta-content {
    padding: 48px 32px;
  }
}

@media (max-width: 599px) {
  .features-section {
    padding: 60px 16px;
  }

  .feature-content {
    padding: 0 20px;
  }

  .feature-icon {
    font-size: 2.5rem;
  }

  .icon-background {
    width: 70px;
    height: 70px;
  }

  .cta-content {
    padding: 40px 24px;
  }

  .cta-buttons {
    flex-direction: column;
    gap: 16px;
  }

  .cta-btn-primary {
    margin-right: 0 !important;
  }
}

/* Professional Animations */
[data-aos] {
  opacity: 0;
  transition-property: transform, opacity;
}

[data-aos].aos-animate {
  opacity: 1;
}

/* Micro Interactions */
.feature-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
  transition: left 0.6s ease;
}

.feature-card:hover::before {
  left: 100%;
}

/* Enhanced Focus States */
.feature-btn:focus-visible,
.cta-btn-primary:focus-visible,
.cta-btn-secondary:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}
</style>
