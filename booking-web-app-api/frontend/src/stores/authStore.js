// src/stores/authStore.js
import { defineStore } from "pinia";
import { api } from "boot/axios"; // axios instance from boot/axios.js

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: null,
    role: null, // 'user' | 'merchant' | 'admin'
  }),

  actions: {
    // REGISTER
    // REGISTER
async register(payload) {
  try {
    let res;

    if (payload.role === "merchant") {
      res = await api.post("/api/merchant/register", payload);
    } else {
      res = await api.post("/api/users/register", payload);
    }

    this.user = res.data.user;
    this.token = res.data.token;
    this.role = res.data.user.role;   // ✅ sirf backend ka role

    localStorage.setItem("token", this.token);
    localStorage.setItem("user", JSON.stringify(this.user));
    localStorage.setItem("role", this.role);

    // Redirect after register
    if (this.role === "merchant") {
      this.router.push("/merchant/dashboard");
    } else {
      this.router.push("/user/profile");
    }
  } catch (err) {
    console.error("Register failed:", err.response?.data || err.message);
    throw err;
  }
},

    // LOGIN
    async login(payload) {
      try {
        let res;

        if (payload.role === "merchant") {
          res = await api.post("/api/merchant/login", payload);
        } else {
          res = await api.post("/api/users/login", payload);
        }

        this.user = res.data.user;
        this.token = res.data.token;
        this.role = payload.role;
        this.role = res.data.user.role;

        localStorage.setItem("token", this.token);
        localStorage.setItem("user", JSON.stringify(this.user));
        localStorage.setItem("role", this.role);

        if (this.role === "merchant") {
          this.router.push("/merchant/dashboard");
        } else if (this.role === "admin") {
          this.router.push("/admin/dashboard");
        } else {
          this.router.push("/user/profile");
        }
      } catch (err) {
        console.error("Login failed:", err.response?.data || err.message);
        throw err;
      }
    },

    logout() {
      this.user = null;
      this.token = null;
      this.role = null;

      localStorage.removeItem("token");
      localStorage.removeItem("user");
      localStorage.removeItem("role");

      this.router.push("/auth/login");
    },
  },
});
