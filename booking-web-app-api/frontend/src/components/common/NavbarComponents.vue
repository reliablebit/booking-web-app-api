<template>
  <q-header elevated class="bg-white text-primary">
    <q-toolbar>
      <!-- Logo Section -->
      <q-toolbar-title class="row items-center cursor-pointer">
        <router-link to="/" class="logo-text q-ml-sm text-primary" style="text-decoration: none">
          BookingMaster
        </router-link>
      </q-toolbar-title>

      <q-space />

      <!-- Desktop Navigation -->
      <div class="desktop-nav q-gutter-sm row items-center no-wrap gt-sm">
        <q-btn flat label="Home" color="primary" @click="scrollToSection('hero')" />
        <q-btn flat label="Features" color="primary" @click="scrollToSection('features')" />
        <q-btn flat label="Pricing" color="primary" @click="scrollToSection('pricing')" />
        <q-btn flat label="Testimonials" color="primary" @click="scrollToSection('testimonials')" />

        <!-- Styled Register & Login -->
        <q-btn unelevated label="Register" color="dark" text-color="white" to="/register-choice" />
        <q-btn outline label="Login" color="dark" to="/login" />
      </div>

      <!-- Mobile Hamburger -->
      <q-btn flat round dense icon="menu" color="primary" class="lt-md" @click="toggleMobileMenu" />
    </q-toolbar>

    <!-- Mobile Drawer -->
    <q-drawer v-model="mobileMenuOpen" side="right" bordered :width="280" class="bg-white">
      <q-scroll-area class="fit">
        <q-list padding class="menu-list">
          <q-item clickable @click="scrollToSection('hero')">
            <q-item-section avatar><q-icon name="home" color="primary" /></q-item-section>
            <q-item-section>Home</q-item-section>
          </q-item>

          <q-item clickable @click="scrollToSection('features')">
            <q-item-section avatar>
              <q-icon name="featured_play_list" color="primary" />
            </q-item-section>
            <q-item-section>Features</q-item-section>
          </q-item>

          <q-item clickable @click="scrollToSection('pricing')">
            <q-item-section avatar>
              <q-icon name="attach_money" color="primary" />
            </q-item-section>
            <q-item-section>Pricing</q-item-section>
          </q-item>

          <q-item clickable @click="scrollToSection('testimonials')">
            <q-item-section avatar>
              <q-icon name="record_voice_over" color="primary" />
            </q-item-section>
            <q-item-section>Testimonials</q-item-section>
          </q-item>

          <q-separator />

          <!-- Mobile Register & Login -->
          <q-item clickable v-close-popup to="/auth/register">
            <q-item-section avatar><q-icon name="person_add" color="dark" /></q-item-section>
            <q-item-section class="text-dark">Register</q-item-section>
          </q-item>

          <q-item clickable v-close-popup to="/auth/login">
            <q-item-section avatar><q-icon name="login" color="dark" /></q-item-section>
            <q-item-section class="text-dark">Login</q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>
  </q-header>
</template>

<script>
export default {
  name: 'NavbarComponent',
  data() {
    return {
      mobileMenuOpen: false,
    }
  },
  methods: {
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen
    },
    scrollToSection(id) {
      this.mobileMenuOpen = false
      setTimeout(() => {
        const section = document.getElementById(id)
        if (section) {
          section.scrollIntoView({ behavior: 'smooth' })
        }
      }, 300)
    },
  },
}
</script>

<style scoped>
.logo-text {
  font-weight: 600;
}
.q-header {
  display: flex;
  height: 60px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.q-menu {
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}
.q-btn::before {
  border-radius: 6px;
}
.q-btn:hover::before {
  background: rgba(25, 118, 210, 0.08);
}
.menu-list .q-item {
  border-radius: 8px;
  margin: 4px 8px;
}
.menu-list .q-item__section--avatar .q-icon {
  font-size: 20px;
}
@media (max-width: 767px) {
  .desktop-nav {
    display: none;
  }
}
@media (min-width: 768px) {
  .mobile-menu-btn {
    display: none;
  }
}
</style>
