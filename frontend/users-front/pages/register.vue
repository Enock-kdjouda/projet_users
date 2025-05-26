<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <form @submit.prevent="register" class="bg-white p-8 rounded shadow-md w-96">
      <h2 class="text-2xl font-bold mb-4">Inscription</h2>
      <input v-model="form.name" type="text" placeholder="Nom" class="input" />
      <input v-model="form.email" type="email" placeholder="Email" class="input" />
      <input v-model="form.password" type="password" placeholder="Mot de passe" class="input" />
      <select v-model="form.role" class="input">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>
      <button class="btn-primary w-full">Créer un compte</button>
      <div class="mt-4 text-center">
        <NuxtLink to="/login" class="text-blue-500">Se connecter</NuxtLink>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'

const form = reactive({ name: '', email: '', password: '', role: 'user' })
const router = useRouter()
const auth = useAuthStore()

async function register() {
  try {
    await auth.register(form)
    router.push('/login')
  } catch (err) {
    alert('Erreur d\'inscription')
  }
}
</script>


<style scoped>
.input { @apply w-full mb-3 p-2 border rounded }
.btn-primary { @apply bg-green-500 hover:bg-green-600 text-white py-2 rounded }
</style>