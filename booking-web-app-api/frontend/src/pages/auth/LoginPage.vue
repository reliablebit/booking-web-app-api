<template>
  <q-page class="flex flex-center bg-grey-2 q-pa-md login-page">
    <div class="login-container shadow-10">
      <!-- Left section with image -->
      <div class="left-section">
        <div class="image-container">
          <img
            src="https://images.unsplash.com/photo-1526947425960-945c6e72858f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"
            alt="Login"
            class="login-image"
          />
          <div class="image-overlay">
            <h3 class="text-h4 text-white">Welcome Back</h3>
            <p class="text-subtitle1 text-white">Login to access your account</p>
          </div>
        </div>
      </div>

      <!-- Right section with form -->
      <div class="right-section">
        <q-form class="login-form q-pa-xl" @submit="onSubmit">
          <h2 class="text-h4 text-primary q-mb-md">Login</h2>
          <p class="text-subtitle2 text-grey-7 q-mb-xl">Enter your credentials to continue</p>

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
            :rules="[(val) => (val && val.length > 0) || 'Please enter your password']"
            class="q-mb-lg"
          >
            <template v-slot:prepend>
              <q-icon name="lock" class="text-primary" />
            </template>
          </q-input>

          <!-- Submit Button -->
          <div class="flex flex-center">
            <q-btn
              label="Login"
              type="submit"
              color="primary"
              class="full-width q-py-sm"
              size="lg"
            />
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios'

export default {
  name: 'LoginPage',
  setup() {
    const $q = useQuasar()
    const router = useRouter()

    const email = ref('')
    const password = ref('')

    async function onSubmit() {
      try {
        const res = await api.post('/api/users/login', {
          email: email.value,
          password: password.value,
          role: 'admin', // force admin role
        })

        const { token } = res.data

        localStorage.setItem('token', token)
        localStorage.setItem('role', 'admin')

        $q.notify({
          color: 'positive',
          message: 'Login successful!',
          icon: 'check_circle',
        })

        // Directly admin dashboard par bhejo
        router.push('/admin/dashboard')
      } catch (err) {
        $q.notify({
          color: 'negative',
          message: err.response?.data?.message || 'Login failed',
          icon: 'error',
        })
      }
    }

    return { email, password, onSubmit }
  },
}
</script>

<style scoped>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  /* min-height: calc(100vh - 60px);  */
  padding: 40px 0; /* Top & bottom spacing */
}

.login-container {
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

.login-image {
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

.login-form {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.social-btn {
  width: 30%;
  border-radius: 8px;
}

@media (max-width: 768px) {
  .login-container {
    flex-direction: column;
    height: auto;
  }

  .left-section {
    height: 200px;
  }

  .social-btn {
    width: 28%;
  }
}
</style>
