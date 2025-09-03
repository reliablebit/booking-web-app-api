<template>
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div
      class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"
    ></div>
  </div>
</template>

<script>
export default {
  name: 'LoaderComponent',
}
</script>

<style scoped>
.loader {
  border-top-color: #3490dc;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
