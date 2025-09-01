<template>
  <q-table
    title="Merchants"
    :rows="rows"
    :columns="columns"
    row-key="id"
    flat
    bordered
  />
</template>

<script setup>
import { ref } from "vue";

const columns = [
  { name: "id", label: "ID", field: "id", align: "left" },
  { name: "business", label: "Business", field: "business", align: "left" },
  { name: "owner", label: "Owner", field: "owner", align: "left" },
];

const rows = ref([
  { id: 1, business: "Cinema One", owner: "Ahmed Khan" },
  { id: 2, business: "Star Multiplex", owner: "Fatima Ali" },
]);
</script>
