<template>
  <q-table
    title="Payments"
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
  { name: "user", label: "User", field: "user", align: "left" },
  { name: "amount", label: "Amount", field: "amount", align: "right" },
  { name: "status", label: "Status", field: "status", align: "left" },
];

const rows = ref([
  { id: 1, user: "Ali Khan", amount: "500 PKR", status: "Paid" },
  { id: 2, user: "Sara Ahmed", amount: "300 PKR", status: "Pending" },
]);
</script>
