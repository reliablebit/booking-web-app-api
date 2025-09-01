<template>
  <section class="hero-section" id="hero">
    <div class="hero-background">
      <div class="hero-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
      </div>
    </div>

    <div class="container">
      <div class="row items-center justify-between">
        <!-- Content Column -->
        <div class="col-lg-6 col-md-12 hero-content">
          <div class="content-wrapper">
            <h1 class="hero-title">
              <span class="gradient-text">Revolutionize</span> Your <br />Booking Management
            </h1>

            <p class="hero-subtitle">
              The all-in-one platform that empowers users, merchants, and administrators to manage
              bookings with unparalleled efficiency and ease.
            </p>

            <div class="cta-buttons q-gutter-md q-mt-xl">
              <q-btn
                label="Get Started Free"
                color="primary"
                size="lg"
                to="/auth/register"
                class="cta-btn primary"
                icon-right="arrow_forward"
              />
              <q-btn
                label="Watch Demo"
                color="dark"
                size="lg"
                outline
                class="cta-btn secondary"
              >
                <q-icon name="play_circle" class="q-mr-sm" />
              </q-btn>
            </div>
            
          </div>
        </div>

        <!-- Image Column -->
        <div class="col-lg-6 col-md-12 hero-visual">
          <div class="visual-wrapper" data-aos="fade-up" data-aos-delay="300">
            <div class="main-dashboard">
              <q-img
                src="https://pabau.com/wp-content/uploads/2024/01/BLOG_Optimizing-Bookings_-The-5-Best-Management-Systems-for-Your-Business.jpg"
                alt="Booking Management Dashboard"
                class="dashboard-image"
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
  name: 'HeroSection',
  mounted() {
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 1000,
        once: true,
      })
    }
  },
}
</script>

<style scoped>
.hero-section {
  position: relative;
  /* min-height: 100vh; */
  display: flex;
  align-items: center;
  overflow: hidden;
  /* background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #e2e8f0 100%); */
  background-color: white;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 0;
}

.hero-shapes {
  position: relative;
  width: 100%;
  height: 100%;
}

.shape {
  position: absolute;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--q-primary) 0%, var(--q-secondary) 100%);
  opacity: 0.1;
}

.shape-1 {
  width: 300px;
  height: 300px;
  top: -150px;
  right: -150px;
}

.shape-2 {
  width: 200px;
  height: 200px;
  bottom: 100px;
  left: -100px;
}

.shape-3 {
  width: 150px;
  height: 150px;
  top: 50%;
  left: 10%;
}

.shape-4 {
  width: 100px;
  height: 100px;
  bottom: 200px;
  right: 20%;
}

.container {
  position: relative;
  z-index: 2;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.hero-content {
  padding: 40px 0;
}

.content-wrapper {
  max-width: 540px;
}

.badge-section {
  animation: fadeInUp 1s ease;
}

.version-badge {
  padding: 8px 16px;
  font-weight: 600;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 24px;
  color: #1a202c;
  animation: fadeInUp 1s ease 0.2s both;
}

.gradient-text {
  background: linear-gradient(135deg, var(--q-primary) 0%, var(--q-secondary) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-subtitle {
  font-size: 1.25rem;
  line-height: 1.6;
  color: #64748b;
  margin-bottom: 0;
  animation: fadeInUp 1s ease 0.4s both;
}

.cta-buttons {
  animation: fadeInUp 1s ease 0.6s both;
}

.cta-btn {
  border-radius: 12px;
  font-weight: 600;
  padding: 12px 32px;
  text-transform: none;
}

.cta-btn.primary {
  background: linear-gradient(135deg, var(--q-primary) 0%, var(--q-secondary) 100%);
  border: none;
}


.hero-visual {
  padding: 40px 0;
  position: relative;
}

.visual-wrapper {
  position: relative;
  max-width: 600px;
  margin: 0 auto;
}

.main-dashboard {
  position: relative;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
  background: white;
}

.dashboard-image {
  width: 100%;
  height: auto;
  display: block;
}

.stats-section {
  margin-top: 80px;
  padding: 40px 0;
  border-top: 1px solid #e2e8f0;
  animation: fadeInUp 1s ease 1s both;
}

.stat-item {
  text-align: center;
  padding: 20px 40px;
}

.stat-number {
  font-size: 3rem;
  line-height: 1;
  margin-bottom: 8px;
}

/* Animations */
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

/* Responsive Design */
@media (max-width: 1023px) {
  .hero-title {
    font-size: 2.5rem;
  }

  .hero-section {
    min-height: auto;
    padding: 80px 0;
  }

  .hero-content,
  .hero-visual {
    text-align: center;
    padding: 20px 0;
  }

  .content-wrapper {
    margin: 0 auto;
  }

  .trust-badges .row {
    justify-content: center;
  }
}

@media (max-width: 599px) {
  .hero-title {
    font-size: 2rem;
  }

  .hero-subtitle {
    font-size: 1.1rem;
  }

  .stat-item {
    padding: 15px 20px;
  }

  .stat-number {
    font-size: 2rem;
  }

  .cta-buttons {
    flex-direction: column;
    align-items: center;
  }

  .cta-btn {
    width: 100%;
    max-width: 280px;
    margin-bottom: 12px;
  }
}
</style>
