<template>
  <header class="flex justify-between items-center bg-white shadow px-4 py-3 mb-4 rounded">
    <div class="text-xl font-semibold">Mon Application</div>

    <div v-if="user" class="flex items-center space-x-4">
      <span class="text-gray-700">👤 {{ user.name }} ({{ user.role }})</span>
      <button @click="logout" class="bg-gray-500 hover:bg-white-200 text-black px-3 py-1 rounded">
        Déconnexion
      </button>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const user = auth.user
console.log('USER DANS L\'EN-TÊTE :', user)

const logout = async () => {
  await auth.logout()
}
</script>
