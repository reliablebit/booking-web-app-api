<template>
  <q-page class="q-pa-md">
    <q-table
      title="All Users"
      :rows="users"
      :columns="columns"
      row-key="id"
    />
  </q-page>
</template>

<script setup>
import { useAdminStore } from 'src/stores/adminStore'
import { onMounted, computed } from 'vue'

const adminStore = useAdminStore()

onMounted(() => {
  adminStore.fetchUsers()
})

const users = computed(() => adminStore.users)

const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left' },
  { name: 'name', label: 'Name', field: 'name', align: 'left' },
  { name: 'email', label: 'Email', field: 'email', align: 'left' }
]
</script>
