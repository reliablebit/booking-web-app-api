<!-- src/pages/landing/LandingPage.vue -->
<template>
  <div class="landing-page">
    <div id="hero">
      <HeroSection />
    </div>

    <div id="features">
      <FeaturesSection />
    </div>

    <div id="pricing">
      <PricingPlans />
    </div>

    <div id="testimonials">
      <TestimonialsSection />
    </div>

    <div id="contact">
      <CallToAction />
    </div>
  </div>
</template>

<script>
import HeroSection from '../../components/landing/HeroSection.vue'
import FeaturesSection from '../../components/landing/FeaturesSection.vue'
import TestimonialsSection from '../../components/landing/TestimonialsSection.vue'
import PricingPlans from '../../components/landing/PricingPlans.vue'
import CallToAction from '../../components/landing/CallToAction.vue'

export default {
  name: 'LandingPage',
  components: {
    HeroSection,
    FeaturesSection,
    TestimonialsSection,
    PricingPlans,
    CallToAction,
  },
  mounted() {
    // Add scroll spy functionality (optional)
    this.handleScrollSpy()
  },
  methods: {
    handleScrollSpy() {
      const sections = ['hero', 'features', 'testimonials', 'pricing', 'contact']
      const navLinks = document.querySelectorAll('.nav-link')

      window.addEventListener('scroll', () => {
        let current = ''

        sections.forEach((section) => {
          const element = document.getElementById(section)
          if (element) {
            const rect = element.getBoundingClientRect()
            if (rect.top <= 100 && rect.bottom >= 100) {
              current = section
            }
          }
        })

        // Update active navigation link
        navLinks.forEach((link) => {
          link.classList.remove('active-section')
          if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active-section')
          }
        })
      })
    },
  },
}
</script>

<style scoped>
.landing-page {
  padding-top: 80px; /* Account for fixed navbar */
}

/* Add smooth transitions between sections */
.landing-page > div {
  scroll-margin-top: 80px; /* Offset for fixed navbar */
}
</style>
