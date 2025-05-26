<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Créer un utilisateur</h1>
    <form @submit.prevent="createUser" class="space-y-4">
      <input v-model="form.name" type="text" placeholder="Nom" class="input" />
      <input v-model="form.email" type="email" placeholder="Email" class="input" />
      <input v-model="form.password" type="password" placeholder="Mot de passe" class="input" />
      <select v-model="form.role" class="input">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>
      <button class="btn-primary">Créer</button>
    </form>
  </div>
</template>

<script setup>
definePageMeta({ middleware: 'auth' });

const form = reactive({ name: '', email: '', password: '', role: 'user' });
const router = useRouter();

async function createUser() {
  const token = localStorage.getItem('token');
  if (!token) {
    alert("Vous n'êtes pas connecté. Veuillez vous reconnecter.");
    router.push('/login');
    return;
  }
 

  try {
    await $fetch('http://localhost:8000/api/users', {
      method: 'POST',
      body: form,
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    
    router.push('/users');
  } catch (err) {
    console.error(err);
    alert("Erreur lors de la création de l'utilisateur.");
  }
}

</script>

<style scoped>
.input {
  @apply w-full p-2 border rounded;
}
.btn-primary {
  @apply bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600;
}
</style>
