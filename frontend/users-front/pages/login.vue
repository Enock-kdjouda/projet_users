<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <form @submit.prevent="login" class="bg-white p-8 rounded shadow-md w-96">
      <h2 class="text-2xl font-bold mb-4">Connexion</h2>
      <input v-model="form.email" type="email" placeholder="Email" class="input" />
      <input v-model="form.password" type="password" placeholder="Mot de passe" class="input" />
      <div class="text-right text-sm mb-2">
        <NuxtLink to="/forgot-password" class="text-blue-500">Mot de passe oublié ?</NuxtLink>
      </div>
      <button class="btn-primary w-full">Se connecter</button>
      <div class="mt-4 text-center">
        <NuxtLink to="/register" class="text-blue-500">Créer un compte</NuxtLink>
      </div>
    </form>
  </div>
</template>
<script setup>
import { useAuthStore } from '@/stores/auth'

const form = reactive({ email: '', password: '' })
const router = useRouter()
const auth = useAuthStore()

async function login() {
  try {
    await auth.login(form)
    router.push('/users')
  } catch (err) {
    alert('Erreur de connexion')
  }
}
onMounted(() => {
  if (auth.token) {
    router.push('/users')
  }
})
</script>

<style scoped>
.input { @apply w-full mb-3 p-2 border rounded }
.btn-primary { @apply bg-blue-500 hover:bg-blue-600 text-white py-2 rounded }
</style>