<template>
  <section class="q-py-xl bg-grey-1 pricing-section">
    <div class="q-container" style="background-color: white">
      <!-- Header Section -->
      <div class="text-center q-mb-xl">
        <!-- Feature Badge -->
        <div class="feature-badge text-primary text-center">
          <q-icon name="monetization_on" size="sm" class="q-mr-xs" />
          CORE FEATURES
        </div>

        <h2 class="text-h3 text-weight-bold text-dark q-mb-md">Choose Your Plan</h2>
        <p class="text-subtitle1 text-grey-7 q-mb-none">Select the perfect plan for your needs</p>
      </div>

      <!-- Pricing Cards -->
      <div class="row justify-center q-gutter-lg">
        <!-- Basic Plan -->
        <div class="col-12 col-sm-6 col-md-4" style="max-width: 350px">
          <q-card class="pricing-card basic-card full-height">
            <q-card-section class="text-center q-pb-none">
              <div class="plan-name text-h6 text-weight-bold q-mb-sm">Basic</div>
              <div class="price-section q-mb-md">
                <span class="text-h4 text-weight-bold">$5</span>
                <span class="text-subtitle2 text-grey-7">/ month</span>
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <q-list dense class="q-mb-md">
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">Up to 5 projects</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">Basic support</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">1GB storage</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>

            <q-card-actions class="q-pa-md">
              <q-btn
                unelevated
                color="primary"
                text-color="white"
                class="full-width q-py-sm"
                label="Get Started"
                @click="selectPlan('basic')"
              />
            </q-card-actions>
          </q-card>
        </div>

        <!-- Pro Plan (Most Popular) -->
        <div class="col-12 col-sm-6 col-md-4" style="max-width: 350px">
          <q-card class="pricing-card pro-card full-height relative-position">
            <!-- Popular Badge -->
            <q-badge
              color="orange"
              text-color="white"
              class="popular-badge absolute-top-right"
              label="Most Popular"
            />

            <q-card-section class="text-center q-pb-none">
              <div class="plan-name text-h6 text-weight-bold q-mb-sm">Pro</div>
              <div class="price-section q-mb-md">
                <span class="text-h4 text-weight-bold">$15</span>
                <span class="text-subtitle2 text-grey-7">/ month</span>
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <q-list dense class="q-mb-md">
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">Unlimited projects</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">Priority support</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">10GB storage</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">Advanced analytics</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>

            <q-card-actions class="q-pa-md">
              <q-btn
                unelevated
                color="black"
                text-color="white"
                class="full-width q-py-sm"
                label="Choose Pro"
                @click="selectPlan('pro')"
              />
            </q-card-actions>
          </q-card>
        </div>

        <!-- Premium Plan -->
        <div class="col-12 col-sm-6 col-md-4" style="max-width: 350px">
          <q-card class="pricing-card premium-card full-height">
            <q-card-section class="text-center q-pb-none">
              <div class="plan-name text-h6 text-weight-bold q-mb-sm">Premium</div>
              <div class="price-section q-mb-md">
                <span class="text-h4 text-weight-bold">$25</span>
                <span class="text-subtitle2 text-grey-7">/ month</span>
              </div>
            </q-card-section>

            <q-card-section class="q-pt-none">
              <q-list dense class="q-mb-md">
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">Everything in Pro</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">24/7 phone support</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">Unlimited storage</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">Custom integrations</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section side>
                    <q-icon name="check_circle" color="positive" size="sm" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-body2">White-label options</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>

            <q-card-actions class="q-pa-md">
              <q-btn
                unelevated
                color="primary"
                text-color="white"
                class="full-width q-py-sm"
                label="Go Premium"
                @click="selectPlan('premium')"
              />
            </q-card-actions>
          </q-card>
        </div>
      </div>

      <!-- Additional Info -->
      <div class="text-center q-mt-xl">
        <p class="text-body2 text-grey-6">All plans include a 30-day money-back guarantee</p>
        <q-btn flat color="primary" label="Contact Sales" @click="contactSales" class="q-mt-sm" />
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'PricingPlans',
  methods: {
    selectPlan(plan) {
      this.$q.notify({
        type: 'positive',
        message: `You selected the ${plan} plan!`,
        position: 'top',
      })
      console.log('Selected plan:', plan)
    },
    contactSales() {
      this.$q.notify({
        type: 'info',
        message: 'Redirecting to sales contact...',
        position: 'top',
      })
    },
  },
}
</script>

<style lang="scss" scoped>
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
  margin-bottom: 2px;
  margin-top: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.pricing-section {
  background-color: white;
}
.pricing-card {
  border-radius: 14px;
  transition: all 0.3s ease;
  border: 2px solid transparent;

  &:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }
}

.basic-card:hover {
  border-color: $primary;
}

.pro-card {
  border-color: $orange;

  &:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
  }
}

.premium-card:hover {
  border-color: $purple;
}

.popular-badge {
  top: -10px;
  right: 20px;
  border-radius: 12px;
  padding: 4px 12px;
  font-size: 11px;
  font-weight: 600;
}

.plan-name {
  color: $grey-8;
}

.price-section {
  display: flex;
  align-items: baseline;
  justify-content: center;
  gap: 4px;
}

// Responsive adjustments
@media (max-width: 600px) {
  .pricing-card {
    margin: 0 auto 24px auto;
    max-width: 300px;
  }
}
</style>
