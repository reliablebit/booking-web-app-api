<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded shadow-lg w-96">
      <slot></slot>
      <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded" @click="$emit('close')">Close</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ModalComponent',
  props: ['show']
};
</script>
