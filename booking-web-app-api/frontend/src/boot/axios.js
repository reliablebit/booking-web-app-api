// src/boot/axios.js
import { defineBoot } from '#q-app/wrappers'
import axios from 'axios'

const api = axios.create({ baseURL: 'http://34.228.213.152:8001' })

// Add JWT token interceptor
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token') // ya sessionStorage / pinia
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
}, (error) => {
  return Promise.reject(error)
})

export default defineBoot(({ app }) => {
  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$api = api
})

export { api }
