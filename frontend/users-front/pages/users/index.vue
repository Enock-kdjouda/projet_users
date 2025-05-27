<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Liste des utilisateurs</h1>

      <!-- Boutons visibles uniquement par les admins -->
      <div v-if="userRole === 'admin'" class="flex gap-2">
        <NuxtLink to="/users/create" class="btn-primary">Créer un utilisateur</NuxtLink>
        <button @click="exportToExcel" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
          Exporter en Excel
        </button>
      </div>
    </div>

    <input
      v-model="search"
      type="text"
      placeholder="Rechercher un utilisateur par nom ou email"
      class="border p-2 w-full mb-4"
    />

    <table class="w-full border">
      <thead>
        <tr class="bg-gray-200">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Nom</th>
          <th class="p-2 border">Email</th>
          <th class="p-2 border">Rôle</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td class="p-2 border">{{ user.id }}</td>
          <td class="p-2 border">{{ user.name }}</td>
          <td class="p-2 border">{{ user.email }}</td>
          <td class="p-2 border">{{ user.role }}</td>
          <td class="p-2 border space-x-2">
            <NuxtLink :to="`/users/${user.id}`" class="btn-secondary">Voir</NuxtLink>

            <!-- Boutons visibles uniquement par l’admin -->
            <template v-if="userRole === 'admin'">
              <NuxtLink :to="`/users/edit?id=${user.id}`" class="btn-warning">Modifier</NuxtLink>
              <button @click="deleteUser(user.id)" class="btn-danger">Supprimer</button>
            </template>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center space-x-2">
      <button v-if="meta.current_page > 1" @click="page--" class="btn-secondary">Précédent</button>
      <span class="px-3 py-1">{{ meta.current_page }} / {{ meta.last_page }}</span>
      <button v-if="meta.current_page < meta.last_page" @click="page++" class="btn-secondary">Suivant</button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import * as XLSX from 'xlsx'

const users = ref([])
const token = ref('')
const page = ref(1)
const meta = ref({ current_page: 1, last_page: 1 })
const search = ref('')
const userRole = ref('') // pour stocker le rôle

onMounted(async () => {
  if (process.client) {
    token.value = localStorage.getItem('token') || ''
    await getUserRole() // Charger le rôle d'abord
    await fetchUsers()
  }
})

watch([page, search], fetchUsers)

async function getUserRole() {
  if (!token.value) return

  try {
    const response = await $fetch('http://localhost:8000/api/user', {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    })

    userRole.value = response.role
  } catch (error) {
    console.error("Erreur lors de la récupération du rôle:", error)
  }
}

async function fetchUsers() {
  if (!token.value) return

  try {
    const response = await $fetch(`http://localhost:8000/api/users?page=${page.value}&search=${search.value}`, {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    })

    users.value = response.data
    meta.value = {
      current_page: response.current_page,
      last_page: response.last_page
    }
  } catch (err) {
    console.error('Erreur API:', err)
  }
}

async function deleteUser(id) {
  if (!token.value) return

  if (confirm('Confirmer la suppression ?')) {
    try {
      await $fetch(`http://localhost:8000/api/users/${id}`, {
        method: 'DELETE',
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      })
      fetchUsers()
    } catch (err) {
      console.error("Erreur suppression:", err)
    }
  }
}

async function exportToExcel() {
  const allUsers = await fetchAllUsersForExport()

  if (!allUsers.length) {
    alert('Aucun utilisateur à exporter.')
    return
  }

  const worksheet = XLSX.utils.json_to_sheet(allUsers)
  const workbook = XLSX.utils.book_new()
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Utilisateurs')
  XLSX.writeFile(workbook, 'liste_utilisateurs.xlsx')
}

async function fetchAllUsersForExport() {
  if (!token.value) return []

  let allUsers = []
  let currentPage = 1
  let lastPage = 1

  do {
    try {
      const response = await $fetch(`http://localhost:8000/api/users?page=${currentPage}&search=${search.value}`, {
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      })

      allUsers = [...allUsers, ...response.data]
      lastPage = response.last_page
      currentPage++
    } catch (error) {
      console.error('Erreur pendant le chargement des utilisateurs:', error)
      break
    }
  } while (currentPage <= lastPage)

  return allUsers
}
</script>

<style scoped>
.btn-primary {
  @apply bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600;
}
.btn-secondary {
  @apply bg-gray-300 text-black px-3 py-1 rounded hover:bg-gray-400;
}
.btn-warning {
  @apply bg-yellow-400 text-black px-3 py-1 rounded hover:bg-yellow-500;
}
.btn-danger {
  @apply bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600;
}
</style>
