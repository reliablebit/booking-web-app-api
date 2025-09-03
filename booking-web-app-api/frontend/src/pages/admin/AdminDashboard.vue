<template>
  <q-page class="dashboard-page">
    <div class="dashboard-container">
      <!-- Header Section -->
      <div class="dashboard-header">
        <div class="header-content">
          <h2 class="dashboard-title">Admin Dashboard</h2>
          <p class="dashboard-subtitle">Monitor your platform's performance and manage operations</p>
        </div>
        <div class="header-stats">
          <div class="stat-item">
            <q-icon name="trending_up" size="sm" color="positive" />
            <span class="stat-text">+12% this month</span>
          </div>
        </div>
      </div>

      <!-- Main Stats Cards -->
      <div class="stats-grid">
        <div
          v-for="card in dashboardCards"
          :key="card.id"
          class="stat-card"
          @click="navigateToPage(card.route)"
        >
          <div class="card-content">
            <div class="card-header">
              <div class="card-icon-wrapper" :style="{ background: card.iconBg }">
                <q-icon :name="card.icon" size="lg" :color="card.iconColor" />
              </div>
              <q-btn
                flat
                round
                dense
                icon="more_vert"
                size="sm"
                class="card-menu-btn"
                @click.stop="showCardMenu"
              >
                <q-tooltip>More options</q-tooltip>
              </q-btn>
            </div>

            <div class="card-body">
              <div class="card-value">{{ card.value }}</div>
              <div class="card-label">{{ card.label }}</div>
              <div class="card-description">{{ card.description }}</div>
            </div>

            <div class="card-footer">
              <div class="trend-indicator" :class="card.trendClass">
                <q-icon :name="card.trendIcon" size="xs" />
                <span class="trend-text">{{ card.trend }}</span>
              </div>
              <div class="card-action">
                <q-icon name="arrow_forward" size="sm" />
              </div>
            </div>
          </div>

          <!-- Hover overlay -->
          <div class="card-overlay">
            <div class="overlay-content">
              <q-icon :name="card.icon" size="xl" color="white" />
              <div class="overlay-text">View {{ card.label }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions Section -->
      <div class="quick-actions-section">
        <h3 class="section-title">Quick Actions</h3>
        <div class="quick-actions-grid">
          <div
            v-for="action in quickActions"
            :key="action.id"
            class="quick-action-card"
            @click="performQuickAction(action.action)"
          >
            <div class="action-icon-wrapper" :style="{ background: action.iconBg }">
              <q-icon :name="action.icon" size="md" :color="action.iconColor" />
            </div>
            <div class="action-content">
              <div class="action-title">{{ action.title }}</div>
              <div class="action-description">{{ action.description }}</div>
            </div>
            <q-icon name="chevron_right" size="sm" color="grey-6" />
          </div>
        </div>
      </div>

      <!-- Recent Activity Section -->
      <div class="recent-activity-section">
        <h3 class="section-title">Recent Activity</h3>
        <div class="activity-timeline">
          <div
            v-for="activity in recentActivities"
            :key="activity.id"
            class="activity-item"
          >
            <div class="activity-icon" :style="{ background: activity.iconBg }">
              <q-icon :name="activity.icon" size="sm" :color="activity.iconColor" />
            </div>
            <div class="activity-content">
              <div class="activity-title">{{ activity.title }}</div>
              <div class="activity-description">{{ activity.description }}</div>
              <div class="activity-time">{{ activity.time }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAdminStore } from 'src/stores/adminStore'

const router = useRouter()
const store = useAdminStore()

// Dashboard cards data
const dashboardCards = ref([
  {
    id: 1,
    label: 'Total Users',
    value: '2,847',
    description: 'Active platform users',
    icon: 'people',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    trend: '+12% from last month',
    trendIcon: 'trending_up',
    trendClass: 'trend-positive',
    route: '/admin/users'
  },
  {
    id: 2,
    label: 'Total Merchants',
    value: '184',
    description: 'Registered merchants',
    icon: 'store',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
    trend: '+8% from last month',
    trendIcon: 'trending_up',
    trendClass: 'trend-positive',
    route: '/admin/merchants'
  },
  {
    id: 3,
    label: 'Active Listings',
    value: '1,329',
    description: 'Property listings',
    icon: 'list',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
    trend: '+15% from last month',
    trendIcon: 'trending_up',
    trendClass: 'trend-positive',
    route: '/admin/listings'
  },
  {
    id: 4,
    label: 'Total Revenue',
    value: 'PKR 2.4M',
    description: 'This month earnings',
    icon: 'payment',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
    trend: '+22% from last month',
    trendIcon: 'trending_up',
    trendClass: 'trend-positive',
    route: '/admin/payments'
  },
  {
    id: 5,
    label: 'Pending Reviews',
    value: '47',
    description: 'Awaiting approval',
    icon: 'pending',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
    trend: '-5% from last week',
    trendIcon: 'trending_down',
    trendClass: 'trend-negative',
    route: '/admin/reviews'
  },
  {
    id: 6,
    label: 'Analytics',
    value: '98.2%',
    description: 'System uptime',
    icon: 'bar_chart',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
    trend: '+0.3% from last week',
    trendIcon: 'trending_up',
    trendClass: 'trend-positive',
    route: '/admin/analytics'
  }
])

// Quick actions data
const quickActions = ref([
  {
    id: 1,
    title: 'Add New User',
    description: 'Create a new user account',
    icon: 'person_add',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    action: 'addUser'
  },
  {
    id: 2,
    title: 'Approve Merchant',
    description: 'Review pending merchant applications',
    icon: 'verified',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
    action: 'approveMerchant'
  },
  {
    id: 3,
    title: 'Generate Report',
    description: 'Create custom analytics report',
    icon: 'assessment',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
    action: 'generateReport'
  },
  {
    id: 4,
    title: 'System Settings',
    description: 'Configure platform settings',
    icon: 'settings',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
    action: 'systemSettings'
  }
])

// Recent activities data
const recentActivities = ref([
  {
    id: 1,
    title: 'New merchant registration',
    description: 'PropertyHub LLC submitted application',
    time: '2 minutes ago',
    icon: 'store',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
  },
  {
    id: 2,
    title: 'Payment processed',
    description: 'PKR 45,000 transaction completed',
    time: '15 minutes ago',
    icon: 'payment',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'
  },
  {
    id: 3,
    title: 'Listing approved',
    description: '3-bedroom apartment in DHA Phase 5',
    time: '1 hour ago',
    icon: 'check_circle',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'
  },
  {
    id: 4,
    title: 'User verification',
    description: 'Ahmad Khan completed profile verification',
    time: '3 hours ago',
    icon: 'verified_user',
    iconColor: 'white',
    iconBg: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'
  }
])

// Methods
const navigateToPage = (route) => {
  router.push(route)
}

const performQuickAction = (action) => {
  // Handle quick actions
  console.log('Performing action:', action)
}

const showCardMenu = () => {
  // Handle card menu
  console.log('Card menu clicked')
}

onMounted(() => {
  // Fetch real data if store exists
  if (store?.fetchAnalytics) {
    store.fetchAnalytics()
  }
})
</script>

<style scoped>
.dashboard-page {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  min-height: 100vh;
  padding: 0;
}

.dashboard-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 32px 24px;
}

/* Header Section */
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
  padding: 24px 0;
}

.header-content h2.dashboard-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1e293b;
  margin: 0 0 8px 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.dashboard-subtitle {
  font-size: 1.1rem;
  color: #64748b;
  margin: 0;
  font-weight: 400;
}

.header-stats {
  display: flex;
  align-items: center;
  gap: 16px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 12px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-text {
  font-weight: 600;
  color: #059669;
  font-size: 0.9rem;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 24px;
  margin-bottom: 48px;
}

.stat-card {
  background: #ffffff;
  border-radius: 20px;
  padding: 0;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.stat-card:hover::before {
  opacity: 1;
}

.stat-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 60px rgba(102, 126, 234, 0.2);
}

.card-content {
  padding: 28px;
  position: relative;
  z-index: 2;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.card-icon-wrapper {
  width: 64px;
  height: 64px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
}

.card-menu-btn {
  color: #94a3b8;
  opacity: 0;
  transition: all 0.3s ease;
}

.stat-card:hover .card-menu-btn {
  opacity: 1;
}

.card-body {
  margin-bottom: 20px;
}

.card-value {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1e293b;
  margin-bottom: 8px;
  line-height: 1;
}

.card-label {
  font-size: 1.1rem;
  font-weight: 600;
  color: #475569;
  margin-bottom: 4px;
}

.card-description {
  font-size: 0.9rem;
  color: #64748b;
  line-height: 1.4;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.trend-indicator {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.trend-positive {
  background: rgba(34, 197, 94, 0.1);
  color: #059669;
}

.trend-negative {
  background: rgba(239, 68, 68, 0.1);
  color: #dc2626;
}

.trend-text {
  font-size: 0.8rem;
}

.card-action {
  opacity: 0;
  transition: all 0.3s ease;
  color: #667eea;
}

.stat-card:hover .card-action {
  opacity: 1;
  transform: translateX(4px);
}

.card-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 20px;
}

.stat-card:hover .card-overlay {
  opacity: 1;
}

.overlay-content {
  text-align: center;
  color: white;
}

.overlay-text {
  font-size: 1.1rem;
  font-weight: 600;
  margin-top: 12px;
}

/* Quick Actions Section */
.quick-actions-section {
  margin-bottom: 48px;
}

.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 24px;
}

.quick-actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 16px;
}

.quick-action-card {
  background: #ffffff;
  border-radius: 16px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.quick-action-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(102, 126, 234, 0.15);
  border-color: #667eea;
}

.action-icon-wrapper {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.action-content {
  flex: 1;
}

.action-title {
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 4px;
}

.action-description {
  font-size: 0.85rem;
  color: #64748b;
}

/* Recent Activity Section */
.recent-activity-section {
  background: #ffffff;
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.activity-timeline {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.activity-item {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 16px;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.activity-item:hover {
  background: rgba(102, 126, 234, 0.05);
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.activity-content {
  flex: 1;
}

.activity-title {
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 4px;
}

.activity-description {
  font-size: 0.9rem;
  color: #475569;
  margin-bottom: 4px;
}

.activity-time {
  font-size: 0.8rem;
  color: #94a3b8;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  }
}

@media (max-width: 768px) {
  .dashboard-container {
    padding: 20px 16px;
  }

  .dashboard-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .header-content h2.dashboard-title {
    font-size: 2rem;
  }

  .stats-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .quick-actions-grid {
    grid-template-columns: 1fr;
  }

  .card-content {
    padding: 20px;
  }

  .card-value {
    font-size: 2rem;
  }

  .recent-activity-section {
    padding: 24px 20px;
  }
}

@media (max-width: 480px) {
  .dashboard-container {
    padding: 16px 12px;
  }

  .header-content h2.dashboard-title {
    font-size: 1.75rem;
  }

  .dashboard-subtitle {
    font-size: 1rem;
  }

  .card-content {
    padding: 16px;
  }

  .card-value {
    font-size: 1.75rem;
  }

  .quick-action-card {
    padding: 16px;
  }

  .action-icon-wrapper {
    width: 40px;
    height: 40px;
  }
}

/* Loading states */
.stat-card.loading {
  pointer-events: none;
}

.stat-card.loading .card-content {
  opacity: 0.6;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .dashboard-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  }

  .stat-card,
  .quick-action-card,
  .recent-activity-section {
    background: #1e293b;
    border-color: #334155;
  }

  .dashboard-title {
    color: #f1f5f9;
  }

  .dashboard-subtitle {
    color: #94a3b8;
  }

  .card-value,
  .card-label,
  .action-title,
  .activity-title {
    color: #f1f5f9;
  }

  .card-description,
  .action-description,
  .activity-description {
    color: #cbd5e1;
  }

  .activity-time {
    color: #64748b;
  }
}

/* Pulse animation for loading */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.loading .card-value,
.loading .card-label {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

</style>
