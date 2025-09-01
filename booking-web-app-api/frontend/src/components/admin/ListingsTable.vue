<template>
  <q-table
    title="Listings"
    :rows="listings"
    :columns="columns"
    row-key="id"
    flat
    bordered
  />
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useAdminStore } from 'src/stores/adminStore'

const adminStore = useAdminStore()

const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left' },
  { name: 'title', label: 'Title', field: 'title', align: 'left' },
  {
    name: 'merchant',
    label: 'Merchant',
    field: row => row.merchant?.business_name || '-',
    align: 'left'
  },
  { name: 'type', label: 'Type', field: 'type', align: 'left' },
  { name: 'price', label: 'Price', field: 'price', align: 'left' },
  { name: 'location', label: 'Location', field: 'location', align: 'left' }
]

// reactivity maintain with computed
const listings = computed(() => adminStore.listings)

onMounted(() => {
  adminStore.fetchListings()
})
</script>
