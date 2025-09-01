<!-- src/pages/auth/MerchantRegister.vue -->
<template>
  <q-page class="flex flex-center bg-grey-2 merchant-register-page">
    <div class="register-container shadow-10">

      <!-- Left Section with Image -->
      <div class="left-section">
        <div class="image-container">
          <img
            src="https://images.unsplash.com/photo-1521791055366-0d553872125f?auto=format&fit=crop&w=500&q=80"
            alt="Merchant Registration"
            class="register-image"
          />
          <div class="image-overlay">
            <h3 class="text-h4 text-white">Grow Your Business</h3>
            <p class="text-subtitle1 text-white">Join us as a merchant and reach more customers</p>
          </div>
        </div>
      </div>

      <!-- Right Section with Form -->
      <div class="right-section">
        <q-form class="register-form q-pa-xl" @submit="onSubmit">
          <h2 class="text-h4 text-secondary q-mb-md">Merchant Account</h2>
          <p class="text-subtitle2 text-grey-7 q-mb-xl">Fill in your business details</p>

          <!-- First Name -->
          <q-input
            filled
            v-model="firstName"
            label="Name"
            lazy-rules
            :rules="[val => !!val || 'Please enter your first name']"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="person" class="text-secondary" />
            </template>
          </q-input>

          <!-- Email -->
          <q-input
            filled
            type="email"
            v-model="email"
            label="Business Email"
            lazy-rules
            :rules="[
              val => !!val || 'Please enter your email',
              val => /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(val) || 'Please enter a valid email address'
            ]"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="mail" class="text-secondary" />
            </template>
          </q-input>

          <!-- Password -->
          <q-input
            filled
            type="password"
            v-model="password"
            label="Password"
            lazy-rules
            :rules="[val => val && val.length >= 8 || 'Password must be at least 8 characters']"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="lock" class="text-secondary" />
            </template>
          </q-input>

          <!-- Phone -->
          <q-input
            filled
            v-model="phone"
            label="Phone Number"
            mask="(###) ###-####"
            unmasked-value
            lazy-rules
            :rules="[val => val && val.length > 0 || 'Please enter your phone number']"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="phone" class="text-secondary" />
            </template>
          </q-input>

          <!-- Company Name -->
          <q-input
            filled
            v-model="companyName"
            label="Company Name"
            lazy-rules
            :rules="[val => !!val || 'Please enter your company name']"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="business" class="text-secondary" />
            </template>
          </q-input>

          <!-- License Number -->
          <q-input
            filled
            v-model="licenseNumber"
            label="License Number"
            lazy-rules
            :rules="[val => !!val || 'Please enter your license number']"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="badge" class="text-secondary" />
            </template>
          </q-input>

          <!-- Terms -->
          <q-checkbox
            v-model="acceptTerms"
            label="I agree to the terms and conditions"
            class="q-mb-lg"
          />

          <!-- Submit -->
          <div class="flex flex-center">
            <q-btn
              label="Register as Merchant"
              type="submit"
              color="secondary"
              class="full-width q-py-sm"
              size="lg"
              :disabled="!acceptTerms"
            />
          </div>

          <!-- Login Link -->
          <p class="text-center q-mt-lg q-mb-none">
            Already have an account?
            <router-link to="/login" class="text-secondary text-weight-medium">Login</router-link>
          </p>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script>
import { ref } from 'vue'
import { useQuasar } from 'quasar'

export default {
  name: "MerchantRegister",
  setup() {
    const $q = useQuasar()

    const firstName = ref('')
    const email = ref('')
    const password = ref('')
    const phone = ref('')
    const companyName = ref('')
    const licenseNumber = ref('')
    const acceptTerms = ref(false)

    function onSubmit() {
      // Form submission logic would go here
      $q.notify({
        color: 'positive',
        message: 'Merchant registration successful!',
        icon: 'check_circle'
      })
    }

    return {
      firstName,
      email,
      password,
      phone,
      companyName,
      licenseNumber,
      acceptTerms,
      onSubmit
    }
  }
}
</script>

<style scoped>
.merchant-register-page {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 0;
}

.register-container {
  display: flex;
  width: 1000px;
  max-width: 90vw;
  min-height: 600px;
  border-radius: 10px;
  overflow: hidden;
  background: white;
}

.left-section {
  flex: 1;
  position: relative;
}

.right-section {
  flex: 1;
}

.image-container {
  width: 100%;
  height: 100%;
  position: relative;
}

.register-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.image-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 30px;
  background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
}

.register-form {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

@media (max-width: 768px) {
  .register-container {
    flex-direction: column;
    height: auto;
  }

  .left-section {
    height: 200px;
  }
}
</style>
