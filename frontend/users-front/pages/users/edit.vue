<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Modifier l'utilisateur</h1>
    <form @submit.prevent="updateUser" class="space-y-4">
      <input v-model="form.name" type="text" placeholder="Nom" class="input" />
      <input v-model="form.email" type="email" placeholder="Email" class="input" />
      <select v-model="form.role" class="input">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>
      <button class="btn-primary">Mettre à jour</button>
    </form>
  </div>
</template>

<script setup>
definePageMeta({ middleware: 'auth' });

const route = useRoute();
const router = useRouter();
const form = reactive({ name: '', email: '', role: 'user' });

onMounted(async () => {
  const id = route.query.id;
  if (!id) {
    alert('ID manquant dans l’URL');
    router.push('/users');
    return;
  }

  const token = localStorage.getItem('token');
  const data = await $fetch(`http://localhost:8000/api/users/${id}`, {
    headers: { Authorization: `Bearer ${token}` },
  });

  form.name = data.name;
  form.email = data.email;
  form.role = data.role;
});

async function updateUser() {
  try {
    const id = route.query.id;
    const token = localStorage.getItem('token');
    await $fetch(`http://localhost:8000/api/users/${id}`, {
      method: 'PUT',
      body: form,
      headers: { Authorization: `Bearer ${token}` },
    });
    router.push('/users');
  } catch (err) {
    alert("Erreur lors de la mise à jour de l'utilisateur");
  }
}
</script>


<style scoped>
.input {
  @apply w-full p-2 border rounded;
}
.btn-primary {
  @apply bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600;
}
</style>
