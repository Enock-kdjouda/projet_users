<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <form @submit.prevent="resetPassword" class="bg-white p-8 rounded shadow-md w-96">
      <h2 class="text-2xl font-bold mb-4">Réinitialiser le mot de passe</h2>
      <input v-model="form.email" type="email" placeholder="Email" class="input" />
      <input v-model="form.token" type="text" placeholder="Token" class="input" />
      <input v-model="form.password" type="password" placeholder="Nouveau mot de passe" class="input" />
      <input v-model="form.password_confirmation" type="password" placeholder="Confirmer le mot de passe" class="input" />
      <button class="btn-primary w-full">Réinitialiser</button>
    </form>
  </div>
</template>

<script setup>
const form = reactive({
  email: '',
  token: '',
  password: '',
  password_confirmation: '',
});

async function resetPassword() {
  try {
    await $fetch('http://localhost:8000/api/reset-password', {
      method: 'POST',
      body: form,
    });
    alert('Mot de passe réinitialisé');
  } catch (err) {
    alert("Erreur lors de la réinitialisation du mot de passe");
  }
}
</script>

<style scoped>
.input {
  @apply w-full mb-3 p-2 border rounded;
}
.btn-primary {
  @apply bg-green-500 hover:bg-green-600 text-white py-2 rounded;
}
</style>
