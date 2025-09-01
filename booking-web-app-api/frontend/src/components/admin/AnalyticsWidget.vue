<template>
  <div class="analytics-container">
    <q-card class="analytics-card">
      <q-card-section class="card-header">
        <div class="header-content">
          <h2 class="analytics-title">
            <q-icon name="analytics" class="q-mr-sm" />
            Analytics Overview
          </h2>
          <p class="analytics-subtitle">Comprehensive platform statistics and insights</p>
        </div>
        <div class="header-actions">
          <q-btn
            flat
            round
            icon="refresh"
            @click="refreshAnalytics"
            :loading="loading"
            class="refresh-btn"
          >
            <q-tooltip>Refresh Data</q-tooltip>
          </q-btn>
          <q-btn
            flat
            round
            icon="download"
            @click="exportData"
            class="export-btn"
          >
            <q-tooltip>Export Data</q-tooltip>
          </q-btn>
        </div>
      </q-card-section>

      <q-separator />

      <q-card-section v-if="analytics && !loading" class="analytics-content">
        <!-- Key Metrics Grid -->
        <div class="metrics-grid">
          <div
            v-for="metric in keyMetrics"
            :key="metric.key"
            class="metric-item"
            :class="`metric-${metric.color}`"
          >
            <div class="metric-icon-wrapper">
              <q-icon
                :name="metric.icon"
                :color="metric.color"
                class="metric-icon"
              />
            </div>
            <div class="metric-content">
              <div class="metric-value">{{ formatMetricValue(metric.value, metric.type) }}</div>
              <div class="metric-label">{{ metric.label }}</div>
              <div class="metric-change" :class="`change-${metric.trend}`">
                <q-icon
                  :name="metric.trend === 'up' ? 'trending_up' : metric.trend === 'down' ? 'trending_down' : 'trending_flat'"
                  size="xs"
                />
                <span>{{ metric.change }}% vs last month</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="detailed-stats">
          <div class="stats-header">
            <h3 class="stats-title">
              <q-icon name="bar_chart" class="q-mr-sm" />
              Detailed Statistics
            </h3>
          </div>

          <q-list separator class="stats-list">
            <q-item
              v-for="stat in detailedStats"
              :key="stat.key"
              class="stat-item"
            >
              <q-item-section avatar>
                <q-avatar
                  :color="stat.color"
                  text-color="white"
                  :icon="stat.icon"
                  size="md"
                />
              </q-item-section>

              <q-item-section>
                <q-item-label class="stat-label">{{ stat.label }}</q-item-label>
                <q-item-label caption class="stat-description">
                  {{ stat.description }}
                </q-item-label>
              </q-item-section>

              <q-item-section side class="stat-value-section">
                <div class="stat-value">{{ formatStatValue(stat.value, stat.type) }}</div>
                <div class="stat-trend" :class="`trend-${stat.trend}`">
                  <q-icon
                    :name="stat.trend === 'up' ? 'arrow_upward' : stat.trend === 'down' ? 'arrow_downward' : 'remove'"
                    size="xs"
                  />
                  <span>{{ stat.change }}%</span>
                </div>
              </q-item-section>
            </q-item>
          </q-list>
        </div>

        <!-- Performance Indicators -->
        <div class="performance-section">
          <div class="stats-header">
            <h3 class="stats-title">
              <q-icon name="speed" class="q-mr-sm" />
              Performance Indicators
            </h3>
          </div>

          <div class="performance-grid">
            <div
              v-for="indicator in performanceIndicators"
              :key="indicator.key"
              class="performance-item"
            >
              <div class="performance-header">
                <q-icon
                  :name="indicator.icon"
                  :color="indicator.color"
                  class="performance-icon"
                />
                <div class="performance-info">
                  <div class="performance-label">{{ indicator.label }}</div>
                  <div class="performance-value">{{ indicator.value }}{{ indicator.unit }}</div>
                </div>
              </div>

              <div class="performance-progress">
                <q-linear-progress
                  :value="indicator.percentage / 100"
                  :color="indicator.color"
                  size="8px"
                  rounded
                  class="progress-bar"
                />
                <div class="progress-text">{{ indicator.percentage }}%</div>
              </div>

              <div class="performance-status" :class="`status-${indicator.status}`">
                <q-icon
                  :name="getStatusIcon(indicator.status)"
                  size="sm"
                />
                <span>{{ indicator.statusText }}</span>
              </div>
            </div>
          </div>
        </div>
      </q-card-section>

      <!-- Loading State -->
      <q-card-section v-else-if="loading" class="loading-section">
        <div class="loading-content">
          <q-spinner-grid color="primary" size="60px" />
          <div class="loading-text">Loading analytics data...</div>
        </div>
      </q-card-section>

      <!-- Error State -->
      <q-card-section v-else class="error-section">
        <div class="error-content">
          <q-icon name="error_outline" color="negative" size="48px" />
          <div class="error-text">Unable to load analytics data</div>
          <q-btn
            color="primary"
            label="Retry"
            @click="refreshAnalytics"
            class="retry-btn"
          />
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import { onMounted, computed, ref } from 'vue'
import { useAdminStore } from 'src/stores/adminStore'

const adminStore = useAdminStore()
const analytics = computed(() => adminStore.analytics)
const loading = ref(false)

// Key metrics configuration
const keyMetrics = computed(() => [
  {
    key: 'users',
    label: 'Total Users',
    value: analytics.value?.total_users || 0,
    type: 'number',
    icon: 'people',
    color: 'blue',
    trend: 'up',
    change: 12.5
  },
  {
    key: 'merchants',
    label: 'Total Merchants',
    value: analytics.value?.total_merchants || 0,
    type: 'number',
    icon: 'store',
    color: 'green',
    trend: 'up',
    change: 8.3
  },
  {
    key: 'listings',
    label: 'Total Listings',
    value: analytics.value?.total_listings || 0,
    type: 'number',
    icon: 'list',
    color: 'orange',
    trend: 'flat',
    change: 0
  },
  {
    key: 'revenue',
    label: 'Total Revenue',
    value: analytics.value?.total_revenue || 0,
    type: 'currency',
    icon: 'payments',
    color: 'purple',
    trend: 'up',
    change: 15.7
  }
])

// Detailed statistics
const detailedStats = computed(() => [
  {
    key: 'bookings',
    label: 'Total Bookings',
    description: 'All time booking count',
    value: analytics.value?.total_bookings || 0,
    type: 'number',
    icon: 'event',
    color: 'teal',
    trend: 'up',
    change: 6.2
  },
  {
    key: 'avg_booking_value',
    label: 'Average Booking Value',
    description: 'Mean transaction amount',
    value: analytics.value?.avg_booking_value || 25000,
    type: 'currency',
    icon: 'trending_up',
    color: 'indigo',
    trend: 'up',
    change: 3.8
  },
  {
    key: 'active_users',
    label: 'Active Users (30 days)',
    description: 'Monthly active user count',
    value: analytics.value?.active_users || Math.floor((analytics.value?.total_users || 0) * 0.6),
    type: 'number',
    icon: 'person',
    color: 'pink',
    trend: 'up',
    change: 9.1
  },
  {
    key: 'conversion_rate',
    label: 'Conversion Rate',
    description: 'Visitor to booking ratio',
    value: 4.2,
    type: 'percentage',
    icon: 'conversion',
    color: 'lime',
    trend: 'up',
    change: 1.3
  }
])

// Performance indicators
const performanceIndicators = computed(() => [
  {
    key: 'user_satisfaction',
    label: 'User Satisfaction',
    value: 4.7,
    unit: '/5.0',
    percentage: 94,
    icon: 'sentiment_very_satisfied',
    color: 'positive',
    status: 'excellent',
    statusText: 'Excellent'
  },
  {
    key: 'platform_uptime',
    label: 'Platform Uptime',
    value: 99.8,
    unit: '%',
    percentage: 99.8,
    icon: 'cloud_done',
    color: 'green',
    status: 'good',
    statusText: 'Very Good'
  },
  {
    key: 'response_time',
    label: 'Avg Response Time',
    value: 245,
    unit: 'ms',
    percentage: 85,
    icon: 'speed',
    color: 'blue',
    status: 'good',
    statusText: 'Good'
  },
  {
    key: 'error_rate',
    label: 'Error Rate',
    value: 0.2,
    unit: '%',
    percentage: 20,
    icon: 'error_outline',
    color: 'orange',
    status: 'warning',
    statusText: 'Needs Attention'
  }
])

// Format metric values
const formatMetricValue = (value, type) => {
  switch (type) {
    case 'currency':
      return `PKR ${value.toLocaleString()}`
    case 'percentage':
      return `${value}%`
    default:
      return value.toLocaleString()
  }
}

// Format stat values
const formatStatValue = (value, type) => {
  switch (type) {
    case 'currency':
      return `PKR ${value.toLocaleString()}`
    case 'percentage':
      return `${value}%`
    default:
      return value.toLocaleString()
  }
}

// Get status icon
const getStatusIcon = (status) => {
  switch (status) {
    case 'excellent':
      return 'check_circle'
    case 'good':
      return 'check'
    case 'warning':
      return 'warning'
    case 'critical':
      return 'error'
    default:
      return 'help'
  }
}

// Refresh analytics data
const refreshAnalytics = async () => {
  loading.value = true
  try {
    await adminStore.fetchAnalytics()
  } catch (error) {
    console.error('Failed to refresh analytics:', error)
  } finally {
    loading.value = false
  }
}

// Export data functionality
const exportData = () => {
  // Implementation for data export
  console.log('Exporting analytics data...')
}

// Initialize data
onMounted(() => {
  if (!analytics.value) {
    refreshAnalytics()
  }
})
</script>

<style scoped>
.analytics-container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}

.analytics-card {
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  background: white;
  overflow: hidden;
}

.card-header {
  padding: 24px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.header-content {
  flex: 1;
}

.analytics-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
}

.analytics-subtitle {
  font-size: 0.9rem;
  color: #64748b;
  margin: 0;
  line-height: 1.4;
}

.header-actions {
  display: flex;
  gap: 8px;
}

.refresh-btn,
.export-btn {
  color: #64748b;
  transition: all 0.3s ease;
}

.refresh-btn:hover,
.export-btn:hover {
  color: #3b82f6;
  background: rgba(59, 130, 246, 0.1);
}

.analytics-content {
  padding: 32px 24px;
}

.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 24px;
  margin-bottom: 40px;
}

.metric-item {
  padding: 20px;
  border-radius: 16px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.metric-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  opacity: 0.05;
  z-index: 0;
}

.metric-blue::before {
  background: #3b82f6;
}

.metric-green::before {
  background: #10b981;
}

.metric-orange::before {
  background: #f59e0b;
}

.metric-purple::before {
  background: #8b5cf6;
}

.metric-item {
  border: 1px solid #e2e8f0;
  background: white;
  display: flex;
  align-items: flex-start;
  gap: 16px;
}

.metric-icon-wrapper {
  background: rgba(var(--q-primary), 0.1);
  border-radius: 12px;
  padding: 12px;
  z-index: 1;
}

.metric-icon {
  font-size: 1.5rem;
}

.metric-content {
  flex: 1;
  z-index: 1;
}

.metric-value {
  font-size: 1.75rem;
  font-weight: 800;
  color: #1e293b;
  line-height: 1.2;
  margin-bottom: 4px;
}

.metric-label {
  font-size: 0.95rem;
  font-weight: 600;
  color: #64748b;
  margin-bottom: 8px;
}

.metric-change {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 0.8rem;
  font-weight: 600;
}

.change-up {
  color: #10b981;
}

.change-down {
  color: #ef4444;
}

.change-flat {
  color: #6b7280;
}

.detailed-stats,
.performance-section {
  margin-bottom: 32px;
}

.stats-header {
  margin-bottom: 20px;
}

.stats-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
  display: flex;
  align-items: center;
}

.stats-list {
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow: hidden;
}

.stat-item {
  padding: 20px;
  transition: background-color 0.2s ease;
}

.stat-item:hover {
  background-color: #f8fafc;
}

.stat-label {
  font-weight: 600;
  color: #1e293b;
  font-size: 0.95rem;
}

.stat-description {
  color: #64748b;
  font-size: 0.85rem;
  margin-top: 2px;
}

.stat-value-section {
  text-align: right;
}

.stat-value {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 4px;
}

.stat-trend {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 4px;
  font-size: 0.8rem;
  font-weight: 600;
}

.trend-up {
  color: #10b981;
}

.trend-down {
  color: #ef4444;
}

.trend-flat {
  color: #6b7280;
}

.performance-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

.performance-item {
  padding: 20px;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  background: white;
  transition: all 0.3s ease;
}

.performance-item:hover {
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  transform: translateY(-2px);
}

.performance-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
}

.performance-icon {
  font-size: 1.5rem;
}

.performance-info {
  flex: 1;
}

.performance-label {
  font-size: 0.9rem;
  font-weight: 600;
  color: #64748b;
  margin-bottom: 4px;
}

.performance-value {
  font-size: 1.5rem;
  font-weight: 800;
  color: #1e293b;
}

.performance-progress {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}

.progress-bar {
  flex: 1;
}

.progress-text {
  font-size: 0.85rem;
  font-weight: 600;
  color: #64748b;
  min-width: 40px;
  text-align: right;
}

.performance-status {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.85rem;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 20px;
  width: fit-content;
}

.status-excellent {
  background: rgba(16, 185, 129, 0.1);
  color: #059669;
}

.status-good {
  background: rgba(34, 197, 94, 0.1);
  color: #16a34a;
}

.status-warning {
  background: rgba(245, 158, 11, 0.1);
  color: #d97706;
}

.status-critical {
  background: rgba(239, 68, 68, 0.1);
  color: #dc2626;
}

.loading-section {
  padding: 60px 24px;
}

.loading-content {
  text-align: center;
}

.loading-text {
  font-size: 1rem;
  color: #64748b;
  margin-top: 16px;
  font-weight: 500;
}

.error-section {
  padding: 60px 24px;
}

.error-content {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}

.error-text {
  font-size: 1.1rem;
  color: #64748b;
  font-weight: 500;
}

.retry-btn {
  margin-top: 8px;
  font-weight: 600;
  border-radius: 12px;
  padding: 8px 24px;
}

/* Responsive Design */
@media (max-width: 768px) {
  .card-header {
    padding: 20px 16px;
    flex-direction: column;
    gap: 16px;
    align-items: stretch;
  }

  .analytics-title {
    font-size: 1.25rem;
  }

  .analytics-content {
    padding: 24px 16px;
  }

  .metrics-grid {
    grid-template-columns: 1fr;
    gap: 16px;
    margin-bottom: 32px;
  }

  .metric-item {
    padding: 16px;
    flex-direction: column;
    text-align: center;
    gap: 12px;
  }

  .metric-value {
    font-size: 1.5rem;
  }

  .performance-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .performance-item {
    padding: 16px;
  }

  .performance-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }

  .performance-value {
    font-size: 1.25rem;
  }

  .stat-item {
    padding: 16px;
  }

  .detailed-stats,
  .performance-section {
    margin-bottom: 24px;
  }
}

@media (max-width: 480px) {
  .card-header {
    padding: 16px 12px;
  }

  .analytics-title {
    font-size: 1.1rem;
  }

  .analytics-content {
    padding: 20px 12px;
  }

  .metric-item {
    padding: 14px;
  }

  .metric-value {
    font-size: 1.25rem;
  }

  .performance-item {
    padding: 14px;
  }

  .performance-value {
    font-size: 1.1rem;
  }

  .stats-title {
    font-size: 1.1rem;
  }

  .loading-section,
  .error-section {
    padding: 40px 12px;
  }
}

/* Animation keyframes */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.metric-item {
  animation: fadeInUp 0.6s ease-out;
}

.metric-item:nth-child(1) { animation-delay: 0.1s; }
.metric-item:nth-child(2) { animation-delay: 0.2s; }
.metric-item:nth-child(3) { animation-delay: 0.3s; }
.metric-item:nth-child(4) { animation-delay: 0.4s; }

.stat-item {
  animation: slideInLeft 0.5s ease-out;
}

.stat-item:nth-child(1) { animation-delay: 0.1s; }
.stat-item:nth-child(2) { animation-delay: 0.2s; }
.stat-item:nth-child(3) { animation-delay: 0.3s; }
.stat-item:nth-child(4) { animation-delay: 0.4s; }

.performance-item {
  animation: fadeInUp 0.6s ease-out;
}

.performance-item:nth-child(1) { animation-delay: 0.1s; }
.performance-item:nth-child(2) { animation-delay: 0.2s; }
.performance-item:nth-child(3) { animation-delay: 0.3s; }
.performance-item:nth-child(4) { animation-delay: 0.4s; }

/* Loading animation */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.loading-content {
  animation: pulse 2s infinite;
}

/* Progress bar animation */
.progress-bar:deep(.q-linear-progress__model) {
  transition: transform 1s ease-in-out;
}

/* Hover effects */
.metric-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.stat-item:hover {
  transform: translateX(4px);
}

.performance-item:hover .performance-icon {
  transform: scale(1.1);
  transition: transform 0.3s ease;
}

/* Focus states for accessibility */
.metric-item:focus,
.performance-item:focus,
.stat-item:focus {
  outline: 2px solid rgba(59, 130, 246, 0.5);
  outline-offset: 2px;
}

.refresh-btn:focus,
.export-btn:focus,
.retry-btn:focus {
  outline: 2px solid rgba(59, 130, 246, 0.5);
  outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  .analytics-card,
  .metric-item,
  .performance-item,
  .stats-list {
    border: 2px solid #000000;
  }

  .metric-change,
  .stat-trend,
  .performance-status {
    border: 1px solid currentColor;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .metric-item,
  .stat-item,
  .performance-item,
  .loading-content {
    animation: none;
    transition: none;
  }

  .metric-item:hover,
  .stat-item:hover,
  .performance-item:hover {
    transform: none;
  }

  .performance-icon {
    transition: none;
  }

  .progress-bar:deep(.q-linear-progress__model) {
    transition: none;
  }
}

/* Print styles */
@media print {
  .analytics-card {
    box-shadow: none !important;
    border: 1px solid #ccc !important;
    break-inside: avoid;
  }

  .header-actions,
  .refresh-btn,
  .export-btn,
  .retry-btn {
    display: none !important;
  }

  .analytics-content {
    padding: 20px !important;
  }

  .metric-item,
  .performance-item {
    break-inside: avoid;
    box-shadow: none !important;
    border: 1px solid #ccc !important;
  }
}

/* Dark theme support (if needed) */
@media (prefers-color-scheme: dark) {
  .analytics-card {
    background: #1e293b;
    border-color: #374151;
  }

  .card-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  }

  .analytics-title,
  .stats-title,
  .metric-value,
  .stat-value,
  .performance-value {
    color: #f8fafc;
  }

  .analytics-subtitle,
  .metric-label,
  .stat-label,
  .stat-description,
  .performance-label,
  .loading-text,
  .error-text {
    color: #cbd5e1;
  }

  .metric-item,
  .performance-item {
    background: #0f172a;
    border-color: #374151;
  }

  .stats-list {
    background: #0f172a;
    border-color: #374151;
  }

  .stat-item:hover {
    background-color: #1e293b;
  }
}

/* Accessibility improvements */
.metric-item,
.stat-item,
.performance-item {
  position: relative;
}

.metric-item:focus-visible,
.stat-item:focus-visible,
.performance-item:focus-visible {
  outline: 3px solid #3b82f6;
  outline-offset: 2px;
}

/* Screen reader only content */
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

/* Custom scrollbar for better UX */
.analytics-content::-webkit-scrollbar {
  width: 6px;
}

.analytics-content::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.analytics-content::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.analytics-content::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

</style>
