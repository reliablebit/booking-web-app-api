<!-- src/pages/auth/UserRegister.vue -->
<template>
  <q-page class="flex flex-center bg-grey-2 user-register-page">
    <div class="register-container shadow-10">
      <!-- Left section with image -->
      <div class="left-section">
        <div class="image-container">
          <img
            src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            alt="Registration"
            class="register-image"
          />
          <div class="image-overlay">
            <h3 class="text-h4 text-white">Join Our Community</h3>
            <p class="text-subtitle1 text-white">Create an account to access exclusive features</p>
          </div>
        </div>
      </div>

      <!-- Right section with form -->
      <div class="right-section">
        <q-form class="register-form q-pa-xl" @submit="onSubmit">
          <h2 class="text-h4 text-primary q-mb-md">Create Account</h2>
          <p class="text-subtitle2 text-grey-7 q-mb-xl">Fill in your details to get started</p>

          <!-- Name Field -->
          <q-input
            filled
            v-model="name"
            label="Full Name"
            lazy-rules
            :rules="[(val) => (val && val.length > 0) || 'Please enter your name']"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="person" class="text-primary" />
            </template>
          </q-input>

          <!-- Email Field -->
          <q-input
            filled
            type="email"
            v-model="email"
            label="Email Address"
            lazy-rules
            :rules="[
              (val) => (val && val.length > 0) || 'Please enter your email',
              (val) =>
                /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(val) || 'Please enter a valid email address',
            ]"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="mail" class="text-primary" />
            </template>
          </q-input>

          <!-- Password Field -->
          <q-input
            filled
            type="password"
            v-model="password"
            label="Password"
            lazy-rules
            :rules="[
              (val) => (val && val.length > 0) || 'Please enter a password',
              (val) => val.length >= 8 || 'Password must be at least 8 characters',
            ]"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="lock" class="text-primary" />
            </template>
          </q-input>

          <!-- Phone Number Field -->
          <q-input
            filled
            v-model="phone"
            label="Phone Number"
            mask="(###) ### - ####"
            unmasked-value
            lazy-rules
            :rules="[(val) => (val && val.length > 0) || 'Please enter your phone number']"
            class="q-mb-md"
          >
            <template v-slot:prepend>
              <q-icon name="phone" class="text-primary" />
            </template>
          </q-input>

          <!-- Terms Agreement -->
          <q-checkbox
            v-model="acceptTerms"
            label="I agree to the terms and conditions"
            class="q-mb-lg"
          />

          <!-- Submit Button -->
          <div class="flex flex-center">
            <q-btn
              label="Register"
              type="submit"
              color="primary"
              class="full-width q-py-sm"
              size="lg"
              :disabled="!acceptTerms"
            />
          </div>

          <!-- Login Link -->
          <p class="text-center q-mt-lg q-mb-none">
            Already have an account?
            <router-link to="/login" class="text-primary text-weight-medium">Login</router-link>
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
  name: 'UserRegister',
  setup() {
    const $q = useQuasar()

    const name = ref(null)
    const email = ref(null)
    const password = ref(null)
    const phone = ref(null)
    const acceptTerms = ref(false)

    function onSubmit() {
      // Form submission logic would go here
      $q.notify({
        color: 'positive',
        message: 'Registration successful!',
        icon: 'check_circle',
      })
    }

    return {
      name,
      email,
      password,
      phone,
      acceptTerms,
      onSubmit,
    }
  },
}
</script>

<style scoped>
.user-register-page {
  display: flex;
  justify-content: center;
  align-items: center;
  /* min-height: calc(100vh - 60px);  */
  padding: 40px 0; /* Top & bottom spacing */
}
.register-container {
  display: flex;
  width: 900px;
  max-width: 90vw;
  min-height: 500px;
  /* height: 600px; */
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
