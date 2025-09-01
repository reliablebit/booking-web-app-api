<template>
  <q-layout view="lHh Lpr lFf" class="admin-layout">
    <!-- Header -->
    <q-header elevated class="admin-header">
      <q-toolbar class="q-px-md toolbar-container">
        <!-- Menu button - only visible on small screens -->
        <q-btn
          dense
          flat
          round
          icon="menu"
          @click="toggleLeftDrawer"
          class="menu-btn lt-lg"
          size="md"
        />

        <q-toolbar-title class="admin-title">
          <q-icon name="admin_panel_settings" size="sm" class="q-mr-sm" />
          Admin Dashboard
        </q-toolbar-title>

        <!-- Navigation Menu - Always visible on large screens -->
        <div class="nav-menu row items-center no-wrap gt-md">
          <q-btn
            flat
            stretch
            label="Dashboard"
            icon="dashboard"
            to="/admin/dashboard"
            class="nav-item"
            :class="{ 'nav-item-active': $route.path === '/admin/dashboard' }"
          />

          <q-btn
            flat
            stretch
            label="Users"
            icon="people"
            to="/admin/users"
            class="nav-item"
            :class="{ 'nav-item-active': $route.path === '/admin/users' }"
          />

          <q-btn
            flat
            stretch
            label="Merchants"
            icon="store"
            to="/admin/merchants"
            class="nav-item"
            :class="{ 'nav-item-active': $route.path === '/admin/merchants' }"
          />

          <q-btn
            flat
            stretch
            label="Listings"
            icon="list"
            to="/admin/listings"
            class="nav-item"
            :class="{ 'nav-item-active': $route.path === '/admin/listings' }"
          />

          <q-btn
            flat
            stretch
            label="Payments"
            icon="payment"
            to="/admin/payments"
            class="nav-item"
            :class="{ 'nav-item-active': $route.path === '/admin/payments' }"
          />

          <q-btn
            flat
            stretch
            label="Analytics"
            icon="bar_chart"
            to="/admin/analytics"
            class="nav-item"
            :class="{ 'nav-item-active': $route.path === '/admin/analytics' }"
          />
        </div>

        <q-space />

        <div class="header-actions row items-center no-wrap">
          <q-btn
            dense
            flat
            round
            icon="notifications"
            class="action-btn q-mr-sm"
            size="md"
          >
            <q-badge color="red" rounded floating>3</q-badge>
            <q-tooltip>Notifications</q-tooltip>
          </q-btn>

          <q-btn
            dense
            flat
            round
            icon="account_circle"
            class="action-btn q-mr-sm"
            size="md"
          >
            <q-tooltip>Profile</q-tooltip>
          </q-btn>

          <q-btn
            dense
            flat
            round
            icon="logout"
            @click="logout"
            class="action-btn"
            size="md"
          >
            <q-tooltip>Logout</q-tooltip>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <!-- Left Drawer - Only for small screens -->
    <q-drawer
      v-model="leftDrawerOpen"
      :show-if-above="false"
      bordered
      :width="280"
      :breakpoint="1024"
      class="drawer-container lt-lg"
    >
      <div class="drawer-header">
        <div class="drawer-logo">
          <q-icon name="admin_panel_settings" size="lg" color="primary" />
          <div class="drawer-title">Admin Panel</div>
        </div>
      </div>

      <q-separator />

      <q-scroll-area class="fit drawer-content">
        <q-list padding class="q-pt-lg">
          <q-item
            v-for="navItem in navItems"
            :key="navItem.label"
            clickable
            v-ripple
            :to="navItem.to"
            @click="leftDrawerOpen = false"
            class="nav-drawer-item"
            :class="{ 'nav-drawer-item-active': $route.path === navItem.to }"
          >
            <q-item-section avatar>
              <q-icon :name="navItem.icon" size="md" />
            </q-item-section>
            <q-item-section>
              <q-item-label class="nav-label">{{ navItem.label }}</q-item-label>
              <q-item-label caption class="nav-caption">{{ navItem.description }}</q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>

    <!-- Page Container -->
    <q-page-container class="page-container">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "src/stores/authStore";
import { useRouter } from "vue-router";

const router = useRouter();

const leftDrawerOpen = ref(false);
const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value;
};

const authStore = useAuthStore();
const logout = () => {
  authStore.logout();
  router.push("/");
};

// Enhanced navigation items with descriptions
const navItems = [
  {
    label: 'Dashboard',
    icon: 'dashboard',
    to: '/admin/dashboard',
    description: 'Overview & Statistics'
  },
  {
    label: 'Users',
    icon: 'people',
    to: '/admin/users',
    description: 'Manage Users'
  },
  {
    label: 'Merchants',
    icon: 'store',
    to: '/admin/merchants',
    description: 'Merchant Management'
  },
  {
    label: 'Listings',
    icon: 'list',
    to: '/admin/listings',
    description: 'Property Listings'
  },
  {
    label: 'Payments',
    icon: 'payment',
    to: '/admin/payments',
    description: 'Transaction History'
  },
  {
    label: 'Analytics',
    icon: 'bar_chart',
    to: '/admin/analytics',
    description: 'Reports & Insights'
  },
];
</script>

<style scoped>
.admin-layout {
  background: #f8fafc;
  min-height: 100vh;
}

.admin-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2);
  backdrop-filter: blur(10px);
}

.toolbar-container {
  min-height: 64px;
  padding: 0 24px;
}

.menu-btn {
  color: rgba(255, 255, 255, 0.9);
  margin-right: 16px;
}

.admin-title {
  font-weight: 700;
  font-size: 1.5rem;
  letter-spacing: 0.5px;
  color: #ffffff;
  display: flex;
  align-items: center;
}

.nav-menu {
  margin-left: 48px;
  gap: 8px;
}

.nav-item {
  color: rgba(255, 255, 255, 0.85);
  font-weight: 600;
  padding: 12px 20px;
  border-radius: 12px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.nav-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.1);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.nav-item:hover::before,
.nav-item-active::before {
  opacity: 1;
}

.nav-item:hover {
  color: #ffffff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.nav-item-active {
  color: #ffffff;
  background: rgba(255, 255, 255, 0.2);
}

.nav-item:deep(.q-btn__content) {
  flex-direction: column;
  z-index: 1;
  position: relative;
}

.nav-item:deep(.q-icon) {
  font-size: 1.3rem;
  margin-bottom: 4px;
}

.nav-item:deep(.q-btn__label) {
  font-size: 0.8rem;
  font-weight: 600;
}

.header-actions .action-btn {
  color: rgba(255, 255, 255, 0.9);
  transition: all 0.3s ease;
  border-radius: 50%;
  padding: 8px;
}

.header-actions .action-btn:hover {
  color: #ffffff;
  background: rgba(255, 255, 255, 0.1);
  transform: scale(1.05);
}

.drawer-container {
  background: #ffffff;
  border-right: 1px solid #e5e7eb;
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.05);
}

.drawer-header {
  padding: 24px;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-bottom: 1px solid #e5e7eb;
}

.drawer-logo {
  display: flex;
  align-items: center;
  gap: 12px;
}

.drawer-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1e293b;
  letter-spacing: 0.5px;
}

.drawer-content {
  padding-top: 16px;
}

.nav-drawer-item {
  margin: 8px 16px;
  border-radius: 12px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.nav-drawer-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 12px;
}

.nav-drawer-item:hover::before,
.nav-drawer-item-active::before {
  opacity: 0.1;
}

.nav-drawer-item:hover {
  transform: translateX(8px);
  box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
}

.nav-drawer-item-active {
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
  border-left: 4px solid #667eea;
}

.nav-drawer-item-active .nav-label {
  color: #667eea;
  font-weight: 700;
}

.nav-drawer-item:deep(.q-item__section--avatar) {
  min-width: 48px;
  z-index: 1;
  position: relative;
}

.nav-drawer-item:deep(.q-item__section--main) {
  z-index: 1;
  position: relative;
}

.nav-label {
  font-size: 0.95rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 2px;
}

.nav-caption {
  font-size: 0.75rem;
  color: #6b7280;
  opacity: 0.8;
}

.nav-drawer-item-active .nav-caption {
  color: #667eea;
  opacity: 0.7;
}

.page-container {
  background: #f8fafc;
  min-height: calc(100vh - 64px);
}

/* Responsive Design */
@media (max-width: 1023px) {
  .admin-title {
    font-size: 1.25rem;
  }

  .toolbar-container {
    padding: 0 16px;
  }

  .menu-btn {
    margin-right: 12px;
  }
}

@media (max-width: 768px) {
  .admin-title {
    font-size: 1.1rem;
  }

  .header-actions .action-btn {
    padding: 6px;
  }

  .drawer-header {
    padding: 20px 16px;
  }

  .drawer-title {
    font-size: 1.1rem;
  }

  .nav-drawer-item {
    margin: 6px 12px;
  }

  .nav-label {
    font-size: 0.9rem;
  }

  .nav-caption {
    font-size: 0.7rem;
  }
}

@media (max-width: 480px) {
  .admin-title {
    font-size: 1rem;
  }

  .admin-title .q-icon {
    display: none;
  }

  .toolbar-container {
    min-height: 56px;
  }
}

/* Animation keyframes */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.nav-item,
.nav-drawer-item {
  animation: slideIn 0.3s ease-out;
}

/* Focus states for accessibility */
.nav-item:focus,
.nav-drawer-item:focus,
.action-btn:focus,
.menu-btn:focus {
  outline: 2px solid rgba(255, 255, 255, 0.5);
  outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  .admin-header {
    background: #000000;
    border-bottom: 2px solid #ffffff;
  }

  .nav-item,
  .action-btn {
    border: 1px solid rgba(255, 255, 255, 0.3);
  }

  .drawer-container {
    border-right: 2px solid #000000;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .nav-item,
  .nav-drawer-item,
  .action-btn {
    transition: none;
  }

  .nav-item:hover,
  .nav-drawer-item:hover {
    transform: none;
  }
}
</style>
