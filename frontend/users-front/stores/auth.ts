import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  // Active la persistance automatique du token
  persist: true,

  state: () => ({
    user: null as null | { name: string; role: string },
    token: null as string | null,
  }),

  actions: {
    async login(form: { email: string; password: string }) {
      const { $apiFetch } = useNuxtApp()
      const apiFetch = $apiFetch as typeof $fetch

      const response = await apiFetch<{ token: string; user: any }>('login', {
        method: 'POST',
        body: form,
      })

      this.token = response.token
      this.user = response.user
      localStorage.setItem('token', this.token)
    },

    async register(form: { name: string; email: string; password: string; role: string }) {
      const { $apiFetch } = useNuxtApp()
      const apiFetch = $apiFetch as typeof $fetch
      await apiFetch('register', {
        method: 'POST',
        body: form,
      })
    },

    async getUser() {
      const { $apiFetch } = useNuxtApp()
      const apiFetch = $apiFetch as typeof $fetch

      if (!this.token) {
        this.token = localStorage.getItem('token')
      }

      if (this.token) {
        try {
          this.user = await apiFetch<{ name: string; role: string }>('user')
        } catch (e) {
          this.logout()
        }
      }
    },

    // Appelée automatiquement au démarrage
    async init() {
      this.token = this.token || localStorage.getItem('token')
      if (this.token && !this.user) {
        await this.getUser()
      }
    },

    logout() {
      this.user = null
      this.token = null
      localStorage.removeItem('token')
      navigateTo('/login')
    },
  },
})
