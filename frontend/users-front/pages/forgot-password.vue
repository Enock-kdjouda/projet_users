<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <form @submit.prevent="sendResetLink" class="bg-white p-8 rounded shadow-md w-96">
      <h2 class="text-2xl font-bold mb-4">Mot de passe oublié</h2>
      <input v-model="email" type="email" placeholder="Email" class="input" />
      <button class="btn-primary w-full">Envoyer le lien de réinitialisation</button>
    </form>
  </div>
</template>

<script setup>
const email = ref('');

async function sendResetLink() {
  try {
    await $fetch('http://localhost:8000/api/forgot-password', {
      method: 'POST',
      body: { email: email.value },
    });
    alert('Lien de réinitialisation envoyé');
  } catch (err) {
    alert("Erreur lors de l'envoi du lien");
  }
}
</script>

<style scoped>
.input {
  @apply w-full mb-3 p-2 border rounded;
}
.btn-primary {
  @apply bg-blue-500 hover:bg-blue-600 text-white py-2 rounded;
}
</style>
