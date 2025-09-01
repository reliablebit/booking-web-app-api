<!-- src/layouts/MainLayout.vue -->
<template>
  <q-layout view="hHh lpR fFf">
    <!-- Navbar (Header) -->
    <NavbarComponent />

    <!-- Page content -->
    <q-page-container>
      <router-view />
      <FooterComponent />
    </q-page-container>
  </q-layout>
</template>

<script>
import NavbarComponent from '../components/common/NavbarComponents.vue'
import FooterComponent from '../components/common/FooterComponent.vue'

export default {
  name: 'MainLayout',
  components: {
    NavbarComponent,
    FooterComponent,
  },
}
</script>
