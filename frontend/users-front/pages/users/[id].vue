<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Détails de l'utilisateur</h1>
    <p><strong>Nom :</strong> {{ user.name }}</p>
    <p><strong>Email :</strong> {{ user.email }}</p>
    <p><strong>Rôle :</strong> {{ user.role }}</p>
  </div>
</template>

<script setup>
definePageMeta({ middleware: 'auth' });

const route = useRoute();
const user = ref({});

onMounted(async () => {
  const token = localStorage.getItem('token');
  user.value = await $fetch(`http://localhost:8000/api/users/${route.params.id}`, {
    headers: { Authorization: `Bearer ${token}` },
  });
});
</script>
