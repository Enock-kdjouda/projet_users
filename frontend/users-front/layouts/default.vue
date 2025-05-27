<template>
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white p-4">
      <h2 class="text-xl font-bold mb-4">Tableau de bord</h2>
      <ul class="space-y-2">
        <li><NuxtLink to="/" class="link">Accueil</NuxtLink></li>
        <li><NuxtLink to="/users" class="link">Utilisateurs</NuxtLink></li>
        <li><NuxtLink to="/users/create" class="link">Créer utilisateur</NuxtLink></li>
        <li><NuxtLink to="/login" class="link">Connexion</NuxtLink></li>
        <li><NuxtLink to="/forgot-password" class="link">Mot de passe oublié</NuxtLink></li>
        <li><NuxtLink to="/reset-password" class="link">Réinitialiser mot de passe</NuxtLink></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-gray-100 p-6">
      <slot />
    </main>
  </div>
</template>
<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

onMounted(() => {
  auth.init()
})

</script>

<style scoped>
.link {
  @apply block px-2 py-1 rounded hover:bg-gray-700;
}
</style>
