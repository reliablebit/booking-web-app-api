// src/router/routes.js
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/landing/LandingPage.vue') },

      // Auth
      { path: 'login', component: () => import('pages/auth/LoginPage.vue') },
      { path: 'register-choice', component: () => import('pages/auth/RegisterChoice.vue') },
      { path: 'register-user', component: () => import('pages/auth/UserRegister.vue') },
      { path: 'register-merchant', component: () => import('pages/auth/MerchantRegister.vue') },
      { path: 'otp-verify', component: () => import('pages/auth/OtpVerify.vue') },
    ],
  },

  // User routes
  {
    path: '/user',
    component: () => import('layouts/UserLayout.vue'),
    children: [
      { path: 'profile', component: () => import('pages/user/UserProfile.vue') },
      { path: 'search', component: () => import('pages/user/SearchPage.vue') },
      { path: 'results', component: () => import('pages/user/ResultsPage.vue') },
      { path: 'booking', component: () => import('pages/user/BookingPage.vue') },
      { path: 'eticket', component: () => import('pages/user/ETicketPage.vue') },
    ],
  },

  // Merchant routes
  {
    path: '/merchant',
    component: () => import('layouts/MerchantLayout.vue'),
    children: [
      { path: 'dashboard', component: () => import('pages/merchant/MerchantDashboard.vue') },
      { path: 'create-listing', component: () => import('pages/merchant/ListingPage.vue') },
      { path: 'seat-editor', component: () => import('pages/merchant/SeatEditorPage.vue') },
      { path: 'bookings', component: () => import('pages/merchant/BookingsPage.vue') },
      { path: 'reports', component: () => import('pages/merchant/ReportsPage.vue') },
    ],
  },

  // Admin routes
  {
    path: '/admin',
    component: () => import('layouts/AdminLayout.vue'),
    children: [
      { path: 'dashboard', component: () => import('pages/admin/AdminDashboard.vue') },
      { path: 'users', component: () => import('pages/admin/UsersPage.vue') },
      { path: 'merchants', component: () => import('pages/admin/MerchantsPage.vue') },
      { path: 'listings', component: () => import('pages/admin/ListingsPage.vue') },
      { path: 'payments', component: () => import('pages/admin/PaymentsPage.vue') },
      { path: 'analytics', component: () => import('pages/admin/AnalyticsPage.vue') },
    ],
  },

  // Booking flow
  {
    path: '/booking',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: 'results', component: () => import('pages/booking/SearchResults.vue') },
      { path: 'seat-selection', component: () => import('pages/booking/SeatSelection.vue') },
      { path: 'summary', component: () => import('pages/booking/BookingSummary.vue') },
      { path: 'payment', component: () => import('pages/booking/PaymentPage.vue') },
      { path: 'success', component: () => import('pages/booking/SuccessPage.vue') },
    ],
  },

  // 404 Error
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/error/ErrorNotFound.vue'),
  },
];

export default routes;
