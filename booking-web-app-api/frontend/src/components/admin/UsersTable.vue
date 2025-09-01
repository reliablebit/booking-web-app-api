<template>
  <q-table
    title="Users"
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
  { name: "name", label: "Name", field: "name", align: "left" },
  { name: "email", label: "Email", field: "email", align: "left" },
];

const rows = ref([
  { id: 1, name: "Ali Khan", email: "ali@example.com" },
  { id: 2, name: "Sara Ahmed", email: "sara@example.com" },
]);
</script>
