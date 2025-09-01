<template>
  <div class="register-choice-container">
    <div class="register-choice-card">
      <div class="header-section">
        <h1 class="page-title">Account Registration</h1>
        <p class="page-subtitle">Select your account type to get started</p>
      </div>

      <div class="registration-options">
        <q-card
          class="registration-card user-card"
          flat
          bordered
          clickable
          @click="$router.push('/register-user')"
        >
          <q-card-section class="text-center">
            <q-icon name="person" size="48px" color="primary" class="q-mb-md" />
            <h3 class="card-title">Individual User</h3>
            <p class="card-description">
              Register as a personal user to access our services and make purchases
            </p>
          </q-card-section>

          <q-card-actions class="justify-center q-pb-lg">
            <q-btn
              label="Register as User"
              color="primary"
              unelevated
              size="md"
              class="registration-btn"
            />
          </q-card-actions>
        </q-card>

        <q-card
          class="registration-card merchant-card"
          flat
          bordered
          clickable
          @click="$router.push('/register-merchant')"
        >
          <q-card-section class="text-center">
            <q-icon name="store" size="48px" color="secondary" class="q-mb-md" />
            <h3 class="card-title">Business Merchant</h3>
            <p class="card-description">
              Register as a merchant to sell products and manage your business
            </p>
          </q-card-section>

          <q-card-actions class="justify-center q-pb-lg">
            <q-btn
              label="Register as Merchant"
              color="secondary"
              unelevated
              size="md"
              class="registration-btn"
            />
          </q-card-actions>
        </q-card>
      </div>

      <div class="footer-section">
        <p class="help-text">
          Need assistance? <a href="/support" class="support-link">Contact Support</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegisterChoicePage',

  methods: {
    navigateToUserRegistration() {
      this.$router.push('/auth/register/user')
    },

    navigateToMerchantRegistration() {
      this.$router.push('/auth/register/merchant')
    },
  },
}
</script>

<style scoped>
.register-choice-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
}

.register-choice-card {
  width: 100%;
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  padding: 3rem 2rem;
}

.header-section {
  text-align: center;
  margin-bottom: 3rem;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #2c3e50;
  margin: 0 0 1rem 0;
  letter-spacing: -0.02em;
}

.page-subtitle {
  font-size: 1.125rem;
  color: #64748b;
  margin: 0;
  font-weight: 400;
}

.registration-options {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 3rem;
}

.registration-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border-radius: 12px;
  border: 2px solid #e2e8f0;
  background: white;
  overflow: hidden;
}

.registration-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
  border-color: transparent;
}

.user-card:hover {
  border-color: #1976d2;
}

.merchant-card:hover {
  border-color: #9c27b0;
}

.card-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1a202c;
  margin: 0 0 1rem 0;
}

.card-description {
  font-size: 1rem;
  color: #64748b;
  line-height: 1.6;
  margin: 0;
  padding: 0 1rem;
}

.registration-btn {
  min-width: 180px;
  height: 48px;
  font-weight: 600;
  font-size: 0.95rem;
  border-radius: 8px;
  text-transform: none;
  letter-spacing: 0.025em;
}

.footer-section {
  text-align: center;
  padding-top: 2rem;
  border-top: 1px solid #e2e8f0;
}

.help-text {
  font-size: 0.875rem;
  color: #64748b;
  margin: 0;
}

.support-link {
  color: #1976d2;
  text-decoration: none;
  font-weight: 500;
}

.support-link:hover {
  text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
  .register-choice-container {
    padding: 1rem;
  }

  .register-choice-card {
    padding: 2rem 1.5rem;
  }

  .page-title {
    font-size: 2rem;
  }

  .registration-options {
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
  }

  .card-title {
    font-size: 1.25rem;
  }

  .card-description {
    font-size: 0.9rem;
    padding: 0;
  }

  .registration-btn {
    min-width: 160px;
    height: 44px;
  }
}

@media (max-width: 480px) {
  .page-title {
    font-size: 1.75rem;
  }

  .page-subtitle {
    font-size: 1rem;
  }

  .registration-card {
    border-radius: 8px;
  }

  .registration-btn {
    min-width: 140px;
    height: 42px;
    font-size: 0.9rem;
  }
}
</style>
