// src/stores/admin.js
import { defineStore } from 'pinia'
import { api } from 'boot/axios'

export const useAdminStore = defineStore('admin', {
  state: () => ({
    users: [],
    merchants: [],
    listings: [],
    bookings: [],
    analytics: {
      total_users: 0,
      total_merchants: 0,
      total_listings: 0,
      total_bookings: 0,
      total_revenue: 0
    }
  }),

  actions: {
    async fetchUsers() {
      const res = await api.get('/api/admin/users')
      this.users = res.data
    },
    async fetchMerchants() {
      const res = await api.get('/api/admin/merchants')
      this.merchants = res.data
    },
    async fetchListings() {
      const res = await api.get('/api/admin/listings')
      this.listings = res.data
    },
    async fetchBookings() {
      const res = await api.get('/api/admin/bookings')
      this.bookings = res.data
    },
    async fetchAnalytics() {
      const res = await api.get('/api/admin/analytics')
      console.log('Analytics Response:', res.data) // 🔎 Debug
      this.analytics = res.data
    }
  }
})
