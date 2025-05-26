<template>
  <div>
    <NuxtLayout>
       <Navbar />
    <main class="min-h-screen bg-gray-100">
      <NuxtPage />
    </main>
    </NuxtLayout>
  

  </div>
</template>
<script setup lang="ts">
import Navbar from '~/components/Navbar.vue'

</script>