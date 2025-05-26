export default defineNuxtRouteMiddleware(async (to, from) => {
  const auth = useAuthStore()

  // Si pas de token dans le store, mais dispo dans localStorage
  if (!auth.token && process.client) {
    const token = localStorage.getItem('token')
    if (token) {
      auth.token = token
      await auth.getUser()
    }
  }

  // Si toujours pas de token, redirige
  if (!auth.token && to.path !== '/login' && to.path !== '/register') {
    return navigateTo('/login')
  }
})
