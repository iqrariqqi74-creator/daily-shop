<template>
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" :src="logoImage" alt="AdminLTE Logo" height="60" width="60" />
    </div>

    <!-- Navbar -->
    <Nav />

    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content Area -->
    <div class="content-wrapper">
        <router-view />
    </div>
    <Footer />
</template>

<script>
import Nav from './Nav.vue'
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'

export default {
    name: 'AdminLayout',
    components: {
        Nav,
        Sidebar,
        Footer
    },
    data() {
        return {
            // This logo path can be updated from Laravel Blade via window.adminlte if needed
            logoImage: window.adminlte?.logoImage || '/adminlte/dist/img/AdminLTELogo.png'
        }
    }
}
</script>

<style scoped>
/* Add any layout-specific styles here if needed */
</style>
